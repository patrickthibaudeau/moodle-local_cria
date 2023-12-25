<?php

require_once("../../config.php");

require_once($CFG->dirroot . "/local/cria/classes/forms/edit_entity_form.php");

use local_cria\base;
use local_cria\entity;


global $CFG, $OUTPUT, $USER, $PAGE, $DB, $SITE;

$context = CONTEXT_SYSTEM::instance();

require_login(1, false);
// Get bot id
$bot_id = optional_param('bot_id', 0,PARAM_INT);
$id = optional_param('id', 0, PARAM_INT);

if ($id != 0) {
    $formdata = $DB->get_record('local_cria_entity', array('id' => $id));
} else {
    $formdata = new stdClass();
// Set bot id in formdata
    $formdata->bot_id = $bot_id;
}

// Create form
$mform = new \local_cria\edit_entity_form(null, array('formdata' => $formdata));
if ($mform->is_cancelled()) {
    //Handle form cancel operation, if cancel button is present on form
    redirect($CFG->wwwroot . '/local/cria/bot_entities.php?bot_id=' . $formdata->bot_id);
} else if ($data = $mform->get_data()) {
    if ($data->id == 0) {
        // In sert new record
        $ENTITY = new entity();
        $ENTITY->insert_record($data);
    } else {
        // update record
        $ENTITY = new entity($data->id);
        $ENTITY->update_record($data);
    }
    // Redirect to content page
    redirect($CFG->wwwroot . '/local/cria/bot_entities.php?bot_id=' . $data->bot_id );
} else {
    // Show form
    $mform->set_data($mform);
}


base::page(
    new moodle_url('/local/cria/edit_entity.php', ['bot_id' => $bot_id, 'id' => $id]),
    get_string('edit_entity', 'local_cria'),
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