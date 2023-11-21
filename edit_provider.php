<?php

require_once("../../config.php");

require_once($CFG->dirroot . "/local/cria/classes/forms/edit_provider_form.php");


use local_cria\provider;
use local_cria\base;



global $CFG, $OUTPUT, $USER, $PAGE, $DB, $SITE;

$context = CONTEXT_SYSTEM::instance();

require_login(1, false);
// Get content id
$id= optional_param('id', 0, PARAM_INT);

$PROVIDER = new provider($id);

$formdata = $PROVIDER->get_record();

if ($id) {
    //Loading files into filemanger
    $draftitemid = file_get_submitted_draft_itemid('provider_image');
    file_prepare_draft_area($draftitemid, $context->id, 'local_cria', 'provider', $formdata->id, Base::getFileManagerOptions($context));
    $formdata->provider_image = $draftitemid;
}


// Create form
$mform = new \local_cria\edit_provider_form(null, array('formdata' => $formdata));
if ($mform->is_cancelled()) {
    //Handle form cancel operation, if cancel button is present on form
    redirect($CFG->wwwroot . '/local/cria/providers.php');
} else if ($data = $mform->get_data()) {
    //Add record if id exists, update record otherwise
    if ($data->id) {
        $PROVIDER->update_record($data);
        $id = $data->id;
    } else {
        $id = $PROVIDER->insert_record($data);
    }

    // Saving file from filemanger
    file_save_draft_area_files($data->provider_image, $context->id, 'local_cria', 'provider', $id, Base::getFileManagerOptions($context));

    // Redirect to the content page
    redirect($CFG->wwwroot . '/local/cria/providers.php');
} else {
    // Show form
    $mform->set_data($mform);
}

base::page(
    new moodle_url('/local/cria/edit_content.php', ['id' => $id]),
    get_string('add_provider', 'local_cria'),
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