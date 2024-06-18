<?php

/**
 * This file is part of Cria.
 * Cria is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.
 * Cria is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License along with Cria. If not, see <https://www.gnu.org/licenses/>.
 *
 * @package    local_cria
 * @author     Patrick Thibaudeau
 * @copyright  2024 onwards York University (https://yorku.ca)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


require_once("../../config.php");

require_once($CFG->dirroot . "/local/cria/classes/forms/edit_content_form.php");

use local_cria\base;
use local_cria\bot;
use local_cria\criaparse;
use local_cria\file;
use local_cria\intent;

global $CFG, $OUTPUT, $USER, $PAGE, $DB, $SITE;

$context = CONTEXT_SYSTEM::instance();

require_login(1, false);
// Get bot id
$intent_id = required_param('intent_id', PARAM_INT);
$id = optional_param('id', 0, PARAM_INT);

// Set page title
if ($id != 0) {
    $page_title = get_string('edit_content', 'local_cria');
} else {
    $page_title = get_string('add_content', 'local_cria');
}

$INTENT = new intent($intent_id);
$BOT = new bot($INTENT->get_bot_id());

if ($id != 0) {
    $formdata = $DB->get_record('local_cria_files', array('id' => $id));
    $formdata->bot_id = $INTENT->get_bot_id();
    $formdata->keywords = json_decode($formdata->keywords);
    $formdata->parsingstrategy = $BOT->get_parse_strategy();;
} else {
    $formdata = new stdClass();
// Set bot id in formdata
    $formdata->id = $id;
    $formdata->intent_id = $intent_id;
    $formdata->bot_id = $INTENT->get_bot_id();
    $formdata->lang = $INTENT->get_lang();
    $formdata->faculty = $INTENT->get_faculty();
    $formdata->program = $INTENT->get_program();
    $formdata->parsingstrategy = $BOT->get_parse_strategy();;
}

// Create form
$mform = new \local_cria\edit_content_form(null, array('formdata' => $formdata));
if ($mform->is_cancelled()) {
    //Handle form cancel operation, if cancel button is present on form
    redirect($CFG->wwwroot . '/local/cria/content.php?bot_id=' . $formdata->bot_id . '&intent_id=' . $formdata->intent_id);
} else if ($data = $mform->get_data()) {
    // Get local_cria config
    $config = get_config('local_cria');

    $keywords = '';
    // Convert keywords to JSON
    if (isset($data->keywords)) {
        $keywords = json_encode($data->keywords);
    }

    // get intent
    $INTENT = new \local_cria\intent($data->intent_id);
    $bot_name = $INTENT->get_bot_name();
    $content_data = [
        'intent_id' => $data->intent_id,
        'content' => '',
        'keywords' => $keywords,
        'usermodified' => $USER->id,
        'timemodified' => time(),
        'timecreated' => time(),
    ];

    // Does the folder exist for cria
    $path = $CFG->dataroot . '/temp/cria';
    base::create_directory_if_not_exists($path);
    // Does the folder exist for the bot
    $path = $CFG->dataroot . '/temp/cria/' . $data->intent_id;
    base::create_directory_if_not_exists($path);

    // If id, then simple upload the file using file picker
    if ($data->id) {
        $FILE = new file($id);
//        $data->path = $path;
//        $data->file_content = $mform->get_file_content('importedFile');

        $FILE->update_record($data);
        // Redirect to content page
        redirect($CFG->wwwroot . '/local/cria/content.php?bot_id=' . $data->bot_id . '&intent_id=' . $data->intent_id);
    } else {
        $FILE = new file();

        // Let's get all files from the file area
        $fs = get_file_storage();

        // Get existing files in the file area
        $existing_files = $fs->get_area_files(
            $context->id,
            'local_cria',
            'content',
            $data->intent_id
        );
        // Store existing files. This is required because if these files are not in the draftarea, they will be deleted
        // when the save_draft_area_files is called. We will recreate these files after the save_draft_area_files is called.
        $original_files = [];
        foreach ($existing_files as $temp_file) {
            if ($temp_file->get_filename() == '.' || $temp_file->get_filename() == '') {
                continue;
            } else {
                $temp_file->copy_content_to($path . '/' . $temp_file->get_filename());
                // Add file to original_files array
                $original_files[] = $temp_file->get_filename();
            }
        }

        // Get draft_area_files
        $draft_area_files = file_get_all_files_in_draftarea($data->importedFile, '/');

        // loop through each file and add filename to drat_area_filenames array
        // Draft_area_filenames will be used to compare with the files in the file area
        $draft_area_filenames = [];
        foreach ($draft_area_files as $file) {
            $draft_area_filenames[] = $file->filename;
        }

        // Save all files to the server
        // Then add each individual file to the database
        file_save_draft_area_files(
        // The $data->attachments property contains the itemid of the draft file area.
            $data->importedFile,

            // The combination of contextid / component / filearea / itemid
            // form the virtual bucket that file are stored in.
            $context->id,
            'local_cria',
            'content',
            $data->intent_id,

            [
                'subdirs' => 0,
                'maxbytes' => $CFG->maxbytes,
                'maxfiles' => -1,
            ]
        );

        // Recreate the original files
        foreach ($original_files as $file) {
            $fileinfo = [
                'contextid' => $context->id,   // ID of the context.
                'component' => 'local_cria', // Your component name.
                'filearea' => 'content',       // Usually = table name.
                'itemid' => $data->intent_id,              // Usually = ID of row in table.
                'filepath' => '/',            // Any path beginning and ending in /.
                'filename' => $file,   // Any filename.
            ];
            $fs->create_file_from_pathname($fileinfo, $path . '/' . $file);
        }
    }
    // Now, index the files
    // Create various objects
    $PARSER = new criaparse();
    $INTENT = new intent($data->intent_id);
    $BOT = new bot($INTENT->get_bot_id());
    // set bot parsing strategy
    $bot_parsing_strategy = $BOT->get_parse_strategy();
    // Once again, get area files
    $files = $fs->get_area_files(
        $context->id,
        'local_cria',
        'content',
        $data->intent_id
    );

    // Iterate through each file
    foreach ($files as $file) {
        if ($file->get_filename() != '.' && $file->get_filename() != '' && in_array($file->get_filename(), $draft_area_filenames)) {
            // Insert file type
            $content_data['file_type'] = $FILE->get_file_type_from_mime_type($file->get_mimetype());
            // get file name
            $file_name = $file->get_filename();
            // Convert files to docx based on file type
            $converted_file_name = '';
            // Has file been converted
            $file_was_converted = false;
            // Copy file to path
            $file->copy_content_to($path . '/' . $file_name);

            // Only convert file is $config->convertapi_key is set
            if ($config->convertapi_api_key == '') {
                $content_data['name'] = $file_name;
                $converted_file_name = $file_name;
            } else {
                // Convert file to docx if file is a pdf, html, or doc
                // Otherwise leave as is
                switch ($content_data['file_type']) {
                    case 'pdf':
                    case 'html':
                    case 'doc':
                    case 'rtf':
                        $converted_file = $FILE->convert_file_to_docx($path, $file_name, $content_data['file_type']);
                        // Replace .pdf to docx in filename
                        $converted_file_name = str_replace('.' . $content_data['file_type'], '.docx', $file_name);
                        $content_data['file_type'] = 'docx';
                        $content_data['name'] = $converted_file_name;
                        $file_was_converted = true;
                        break;
                    default:
                        $content_data['name'] = $file_name;
                        if ($content_data['file_type'] == 'docx') {
                            // Copy file to temp path
                            $file->copy_content_to($path . '/' . $file_name);
                        }
                        $converted_file = $path . '/' . $file_name;
                        $converted_file_name = $file_name;
                        break;
                }
            }
            $converted_file_path = $path . '/' . $converted_file_name;

            // If $BOT->get_parse_strategy() is not equal to $data->parsingstrategy, then update $parsing_strategy
            if ($data->parsingstrategy != $BOT->get_parse_strategy()) {
                $bot_parsing_strategy = $data->parsingstrategy;
            }
            // Set parsing strategy based on file type.
            $parsing_strategy = $PARSER->set_parsing_strategy_based_on_file_type(
                $content_data['file_type'],
                $bot_parsing_strategy
            );
            $results = $PARSER->execute(
                $BOT->get_model_id(),
                $BOT->get_embedding_id(),
                $parsing_strategy,
                $path . '/' . $converted_file_name
            );
            if ($results['status'] != 200) {
                \core\notification::error('Error parsing file: ' . $results['message']);
            } else {
                $nodes = $results['nodes'];
                // Send nodes to indexing server
                $upload = $FILE->upload_nodes_to_indexing_server($bot_name, $nodes, $file_name, $content_data['file_type'], false);
                if ($upload->status != 200) {
                    \core\notification::error('Error uploading file to indexing server: ' . $upload->message);
                } else {
                    // Save converted file to moodle
                    if ($file_was_converted) {
                        $fileinfo = [
                            'contextid' => $context->id,   // ID of the context.
                            'component' => 'local_cria', // Your component name.
                            'filearea' => 'content',       // Usually = table name.
                            'itemid' => $data->intent_id,              // Usually = ID of row in table.
                            'filepath' => '/',            // Any path beginning and ending in /.
                            'filename' => $converted_file_name,   // Any filename.
                        ];

                        $fs->create_file_from_pathname($fileinfo, $converted_file_path);
                        // Delete the original file from the file area
                        $file->delete();
                    }
                    // Verification paramaters
                    $content_verification = [
                        'name' => $file_name,
                        'intent_id' => $data->intent_id
                    ];
                    if (!$DB->get_record('local_cria_files', $content_verification)) {
                        // Insert the content into the database
                        $file_id = $DB->insert_record('local_cria_files', $content_data);

                    } else {
                        // Update the content into the database
                        $content_data['id'] = $data->id;
                        $DB->update_record('local_cria_files', $content_data);
                        $update = true;
                    }
                }
            }
            // Delete the file from the server
            base::delete_files($path);
        }
    }

    // Redirect to content page
    redirect($CFG->wwwroot . '/local/cria/content.php?bot_id=' . $data->bot_id . '&intent_id=' . $data->intent_id);
} else {
    // Show form
    $mform->set_data($mform);
}


base::page(
    new moodle_url('/local/cria/edit_content.php'),
    $page_title,
    '',
    $context,
    'standard'
);
$PAGE->requires->css(new moodle_url('/local/cria/css/select2.min.css'));
$PAGE->requires->css(new moodle_url('/local/cria/css/select2-bootstrap4.min.css'));
$PAGE->requires->js_call_amd('local_cria/add_content_form', 'init');

echo $OUTPUT->header();
//**********************
//*** DISPLAY HEADER ***
//
$mform->display();
echo $OUTPUT->render_from_template('local_cria/loader', []);
//**********************
//*** DISPLAY FOOTER ***
//**********************
echo $OUTPUT->footer();
?>