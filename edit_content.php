<?php

require_once("../../config.php");

require_once($CFG->dirroot . "/local/cria/classes/forms/edit_content_form.php");

use local_cria\base;
use local_cria\criabot;
use local_cria\file;
use local_cria\intent;


global $CFG, $OUTPUT, $USER, $PAGE, $DB, $SITE;

$context = CONTEXT_SYSTEM::instance();

require_login(1, false);
// Get bot id
$intent_id = required_param('intent_id', PARAM_INT);
$id = optional_param('id', 0, PARAM_INT);

$INTENT = new intent($intent_id);

if ($id != 0) {
    $formdata = $DB->get_record('local_cria_files', array('id' => $id));
    $formdata->bot_id = $INTENT->get_bot_id();
    $formdata->keywords = json_decode($formdata->keywords);
} else {
    $formdata = new stdClass();
// Set bot id in formdata
    $formdata->id = $id;
    $formdata->intent_id = $intent_id;
    $formdata->bot_id = $INTENT->get_bot_id();
    $formdata->lang = $INTENT->get_lang();
    $formdata->faculty = $INTENT->get_faculty();
    $formdata->program = $INTENT->get_program();
}

// Create form
$mform = new \local_cria\edit_content_form(null, array('formdata' => $formdata));
if ($mform->is_cancelled()) {
    //Handle form cancel operation, if cancel button is present on form
    redirect($CFG->wwwroot . '/local/cria/content.php?bot_id=' . $formdata->bot_id . '&intent_id=' . $formdata->intent_id);
} else if ($data = $mform->get_data()) {

    $keywords = '';
    // Convert keywords to JSON
    if (isset($data->keywords)) {
        $keywords = json_encode($data->keywords);
    }
    unset($data->keywords);
    $FILE = new file();
    // get intent
    $INTENT = new \local_cria\intent($data->intent_id);
    $bot_name = $INTENT->get_bot_name();
    $content_data = [
        'intent_id' => $data->intent_id,
        'content' => '',
        'keywords' => $keywords,
        'lang' => $data->lang,
        'faculty' => $data->faculty,
        'program' => $data->program,
        'usermodified' => $USER->id,
        'timemodified' => time(),
        'timecreated' => time(),
    ];

    // Does the folder exist for cria
    $path = $CFG->dataroot . '/temp/cria';
    if (!is_dir($path)) {
        mkdir($path);
    }
    // Does the folder exist for the bot
    $path = $CFG->dataroot . '/temp/cria/' . $data->intent_id;
    if (!is_dir($path)) {
        mkdir($path);
    }

    // If id, then simple upload the file using file picker
    if ($data->id) {
        // Get record
        $file_record = $DB->get_record('local_cria_files', ['id' => $data->id]);
        $file_name = $file_record->name;

        $file_content = $mform->get_file_content('importedFile');

// Set the file path with filename
        $file_path = $path . '/' . $file_name;
// save the file
        file_put_contents($file_path, $file_content);
// Save file to moodle file system
        $fs = get_file_storage();
        $file_info = [
            'contextid' => $context->id,
            'component' => 'local_cria',
            'filearea' => 'content',
            'itemid' => $data->intent_id,
            'filepath' => '/',
            'filename' => $file_name
        ];
        // First delete the existing file
        $file = $fs->get_file(
            $context->id,
            'local_cria',
            'content',
            $data->intent_id,
            '/',
            $file_name
        );
        if ($file) {
            $file->delete();
        }
        $fs->create_file_from_pathname($file_info, $file_path);
// Get newly created file
        $file = $fs->get_file(
            $context->id,
            'local_cria',
            'content',
            $data->intent_id,
            '/',
            $file_name
        );


// Copy to temp area
        $file->copy_content_to($file_path);

        // Update file on indexing server
        $upload = $FILE->upload_files_to_indexing_server($bot_name, $file_path, $file_name, true);
// Delete the file from the server
        unlink($file_path);
        if ($upload->status != 200) {
            \core\notification::error('Error uploading file to indexing server: ' . $upload->message);
        }
        // Redirect to content page
        redirect($CFG->wwwroot . '/local/cria/content.php?bot_id=' . $data->bot_id . '&intent_id=' . $data->intent_id);
    } else {
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
    }
// Save file information
    // Let's get all files from the file area
    $fs = get_file_storage();

    $files = $fs->get_area_files(
        $context->id,
        'local_cria',
        'content',
        $data->intent_id
    );
    foreach ($files as $file) {
        if ($file->get_filename() != '.' && $file->get_filename() != '') {
            // Insert file name
            $content_data['name'] = $file->get_filename();
//            print_object($file->get_mimetype());
//            die;
            // Insert file type
            $content_data['file_type'] = $FILE->get_file_type_from_mime_type($file->get_mimetype());

            $file_name = $file->get_filename();
            // Verification paramaters
            $content_verification = [
                'name' => $file_name,
                'intent_id' => $data->intent_id
            ];
            if (!$DB->get_record('local_cria_files', $content_verification)) {
                $file_path = $path . '/' . $file_name;
                // Copy to temp area
                $file->copy_content_to($file_path);
                // Insert the content into the database
                $file_id = $DB->insert_record('local_cria_files', $content_data);

            } else {
                // Update the content into the database
                $content_data['id'] = $data->id;
                $DB->update_record('local_cria_files', $content_data);
                $update = true;
            }

// Upload/update files to indexing server
            $upload = $FILE->upload_files_to_indexing_server($bot_name, $file_path, $file_name, false);
// Delete the file from the server
//            unlink($file_path);
            if ($upload->status != 200) {
                \core\notification::error('Error uploading file to indexing server: ' . $upload->message);
            }


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
    get_string('add_content', 'local_cria'),
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

//**********************
//*** DISPLAY FOOTER ***
//**********************
echo $OUTPUT->footer();
?>