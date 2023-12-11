<?php

require_once("../../config.php");

require_once($CFG->dirroot . "/local/cria/classes/forms/add_content_form.php");

use local_cria\rd_text_extraction;
use local_cria\cria;

/**
 * Loads all files found in a given folder.
 * Calls itself recursively for all sub folders.
 *
 * @param string $dir
 */
function requireFilesOfFolder($dir)
{
    foreach (new DirectoryIterator($dir) as $fileInfo) {
        if (!$fileInfo->isDot()) {
            if ($fileInfo->isDir()) {
                requireFilesOfFolder($fileInfo->getPathname());
            } else {
                require_once $fileInfo->getPathname();
            }
        }
    }
}

$rootFolder = __DIR__ . '/classes/Smalot/PdfParser';

// Manually require files, which can't be loaded automatically that easily.
require_once $rootFolder . '/Element.php';
require_once $rootFolder . '/PDFObject.php';
require_once $rootFolder . '/Font.php';
require_once $rootFolder . '/Page.php';
require_once $rootFolder . '/Element/ElementString.php';
require_once $rootFolder . '/Encoding/AbstractEncoding.php';

/*
 * Load the rest of PDFParser files from /src/Smalot/PDFParser
 * Dont worry, it wont load files multiple times.
 */
requireFilesOfFolder($rootFolder);

use local_cria\base;
use local_cria\file;
use local_cria\intent;
use Smalot\PdfParser;


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
} else {
    $formdata = new stdClass();
// Set bot id in formdata
    $formdata->intent_id = $intent_id;
    $formdata->bot_id = $INTENT->get_bot_id();
}

// Create form
$mform = new \local_cria\add_content_form(null, array('formdata' => $formdata));
if ($mform->is_cancelled()) {
    //Handle form cancel operation, if cancel button is present on form
    redirect($CFG->wwwroot . '/local/cria/content.php?id=' . $formdata->bot_id . '&intent_id=' . $formdata->intent_id);
} else if ($data = $mform->get_data()) {
    $FILE = new file();
    // Set update to false
    $update = false;
    // Get file name and content
    if ($data->name == false) {
        $filename = strtolower(str_replace(' ', '_', $mform->get_new_filename('importedFile')));
    } else {
        $filename = $data->name;
    }

    $fileContent = $mform->get_file_content('importedFile');
    $path = $CFG->dataroot . '/temp/cria';

    // Does the folder exist for cria
    if (!is_dir($path)) {
        mkdir($path);
    }
    // Does the folder exist for the bot
    $path = $CFG->dataroot . '/temp/cria/' . $data->intent_id;
    if (!is_dir($path)) {
        mkdir($path);
    }
    // Set the file path with filename
    $file_path = $path . '/' . $filename;
    // save the file
    file_put_contents($file_path, $fileContent);
    // Save file to moodle file system
    $fs = get_file_storage();
    $file_info =[
        'contextid' => $context->id,
        'component' => 'local_cria',
        'filearea' => 'content',
        'itemid' => $data->intent_id,
        'filepath' => '/',
        'filename' => $filename
    ];
    $fs->create_file_from_pathname($file_info, $file_path);
    // Get newly created file
    $file = $fs->get_file(
        $context->id,
        'local_cria',
        'content',
        $data->intent_id,
        '/',
        $filename
    );

    if ($file->get_mimetype() == 'application/vnd.openxmlformats-officedocument.wordprocessingml.document') {
        // Convert file to docx
        $converter = new \core_files\converter();
        $conversion = $converter->start_conversion($file, 'docx');
    }

    // Copy to temp area
    $file->copy_content_to($file_path);
// Delete file created in Moodle file area.
    $file->delete();

    // Convert the content of the file to text
//    $content = rd_text_extraction::convert_to_text($file_path);
    // Remove all lines and replace with space. AKA lower token count
//    $content = preg_replace('/\s+/', ' ', trim($content));
    // Create content data array
    $content_data = [
        'intent_id' => $data->intent_id,
        'content' => '',
        'name' => $filename,
        'usermodified' => $USER->id,
        'timemodified' => time(),
        'timecreated' => time(),
    ];
    // Check to see if the file already exists
    if ($file = $DB->get_record('local_cria_files', ['intent_id' => $data->intent_id, 'name' => $filename])) {
        // Update the content into the database
        $content_data['id'] = $data->id;
        $DB->update_record('local_cria_files', $content_data);
        $update = true;
    } else {
        // Insert the content into the database
        $file_id = $DB->insert_record('local_cria_files', $content_data);
    }
    $INTENT = new \local_cria\intent($data->intent_id);
    $bot_name = $INTENT->get_bot_name();
    // Upload/update files to indexing server
    $upload = $FILE->upload_files_to_indexing_server($bot_name, $file_path, $filename, $update);
    // Delete the file from the server
    unlink($file_path);
    if ($upload->status != 200) {
        \core\notification::error('Error uploading file to indexing server: ' . $upload->message);
    }

    // Redirect to content page
    redirect($CFG->wwwroot . '/local/cria/content.php?id=' . $data->bot_id . '&intent_id=' . $data->intent_id);
} else {
    // Show form
    $mform->set_data($mform);
}


base::page(
    new moodle_url('/local/cria/add_content.php'),
    get_string('add_content', 'local_cria'),
    '',
    $context,
    'standard'
);

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