<?php

require_once("../../config.php");

require_once($CFG->dirroot . "/local/cria/classes/forms/bot_type_form.php");

use local_cria\base;

global $CFG, $OUTPUT, $USER, $PAGE, $DB, $SITE;

$id = optional_param('id', 0, PARAM_INT);

$context = CONTEXT_SYSTEM::instance();

require_login(1, false);

if ($id) {
    $formdata = $DB->get_record('local_cria_type', ['id' => $id]);
} else {
    $formdata = new stdClass();
    $formdata->use_bot_server = true;
}


$mform = new \local_cria\bot_type_form(null, array('formdata' => $formdata));
if ($mform->is_cancelled()) {
    //Handle form cancel operation, if cancel button is present on form
    redirect($CFG->wwwroot . '/local/cria/bot_types.php');
} else if ($data = $mform->get_data()) {
    if ($data->id) {
        $data->usermodified = $USER->id;
        $data->timemodified = time();
        $DB->update_record('local_cria_type', $data);
    } else {
        $data->usermodified = $USER->id;
        $data->timemodified = time();
        $data->timecreated = time();
        $DB->insert_record('local_cria_type', $data);
    }
    redirect($CFG->wwwroot . '/local/cria/bot_types.php');

} else {

    $mform->set_data($mform);
}


base::page(
    new moodle_url('/local/cria/bot.php', ['id' => $id]),
    get_string('bot', 'local_cria'),
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