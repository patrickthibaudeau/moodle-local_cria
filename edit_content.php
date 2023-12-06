<?php

require_once("../../config.php");

require_once($CFG->dirroot . "/local/cria/classes/forms/edit_content_form.php");


use local_cria\base;
use local_cria\file;



global $CFG, $OUTPUT, $USER, $PAGE, $DB, $SITE;

$context = CONTEXT_SYSTEM::instance();

require_login(1, false);
// Get content id
$id= required_param('intent_id', PARAM_INT);

$FILES = new file($id);

$formdata = $FILES->get_record();

// Create form
$mform = new \local_cria\edit_content_form(null, array('formdata' => $formdata));
if ($mform->is_cancelled()) {
    //Handle form cancel operation, if cancel button is present on form
    redirect($CFG->wwwroot . '/local/cria/content.php?id=' . $formdata->bot_id);
} else if ($data = $mform->get_data()) {
    $SFILE = new file($data->bot_id);
//    $data->content = $content = preg_replace('/\s+/', ' ', trim($data->content));
    // Update content
    $DB->update_record('local_cria_files', $data);

    $SFILE->upload_files_to_indexing_server($data->bot_id, $data->id, true);
    // Redirect to the content page
    redirect($CFG->wwwroot . '/local/cria/content.php?id=' . $data->bot_id,);
} else {
    // Show form
    $mform->set_data($mform);
}

base::page(
    new moodle_url('/local/cria/edit_content.php', ['id' => $id]),
    get_string('add_content', 'local_cria'),
    '',
    $context,
    'standard'
);

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