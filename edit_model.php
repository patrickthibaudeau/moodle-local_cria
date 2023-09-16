<?php

require_once("../../config.php");

require_once($CFG->dirroot . "/local/cria/classes/forms/model_form.php");

use local_cria\base;
use local_cria\model;

global $CFG, $OUTPUT, $USER, $PAGE, $DB, $SITE;

$id = optional_param('id', 0, PARAM_INT);

$context = CONTEXT_SYSTEM::instance();

$MODEL = new model($id);

require_login(1, false);

if ($id) {
    $formdata = $MODEL->get_result();
} else {
    $formdata = new stdClass();
}


$mform = new \local_cria\model_form(null, array('formdata' => $formdata));
if ($mform->is_cancelled()) {
    //Handle form cancel operation, if cancel button is present on form
    redirect($CFG->wwwroot . '/local/cria/bot_models.php');
} else if ($data = $mform->get_data()) {
    if ($data->id) {
        $data->usermodified = $USER->id;
        $data->timemodified = time();
        $DB->update_record('local_cria_models', $data);
    } else {
        $data->usermodified = $USER->id;
        $data->timemodified = time();
        $data->timecreated = time();
        $DB->insert_record('local_cria_models', $data);
    }
    redirect($CFG->wwwroot . '/local/cria/bot_models.php');

} else {

    $mform->set_data($mform);
}


base::page(
    new moodle_url('/local/cria/edit_model.php', ['id' => $id]),
    get_string('model', 'local_cria'),
    get_string('model', 'local_cria'),
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