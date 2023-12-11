<?php

require_once("../../config.php");

require_once($CFG->dirroot . "/local/cria/classes/forms/edit_question_form.php");

use local_cria\base;
use local_cria\intent;


global $CFG, $OUTPUT, $USER, $PAGE, $DB, $SITE;

$context = CONTEXT_SYSTEM::instance();

require_login(1, false);
$intent_id = required_param('intent_id',  PARAM_INT);
$id = optional_param('id', 0, PARAM_INT);
$parent_id = optional_param('parent_id', 0, PARAM_INT);

$INTENT = new intent($intent_id);

if ($id != 0) {
    $formdata = $DB->get_record('local_cria_question', array('id' => $id));
    $formdata->bot_id = $INTENT->get_bot_id();
    // Get all question examples
    $examples = $DB->get_records('local_cria_question_example', array('questionid' => $id));
    $formdata->examples = array_values($examples);
    $formdata->create_example_questions = false;
} else {
    $formdata = new stdClass();
    $formdata->id = 0;
    $formdata->intent_id = $INTENT->get_id();
    $formdata->bot_id = $INTENT->get_bot_id();
    $formdata->lang = $INTENT->get_lang();
    $formdata->faculty = $INTENT->get_faculty();
    $formdata->program = $INTENT->get_program();
    $formdata->examples = false;
    $formdata->create_example_questions = true;
    $formdata->parent_id = $parent_id;
}


// Create form
$mform = new \local_cria\edit_question_form(null, array('formdata' => $formdata));
if ($mform->is_cancelled()) {
    //Handle form cancel operation, if cancel button is present on form
    redirect($CFG->wwwroot . '/local/cria/content.php?id=' . $formdata->bot_id);
} else if ($data = $mform->get_data()) {
    $data->usermodified = $USER->id;
    $data->timemodified = time();
    if ($data->id == 0) {
        // Insert record
        $data->timecreated = time();
        $data->id = $DB->insert_record('local_cria_question', $data);
        // Update record adding the question id to the parent_id
        $data->parent_id = $data->id;
        $DB->update_record('local_cria_question', $data);
    } else {
        // Update record
        $DB->update_record('local_cria_question', $data);
    }
    if ($data->create_example_questions == true) {
        $INTENT = new intent($data->intent_id);
        $INTENT->create_example_questions($data->id);
    }

    // Redirect to content page
    redirect($CFG->wwwroot . '/local/cria/edit_question.php?id=' . $data->id . '&intent_id=' . $data->intent_id);
} else {
    // Show form
    $mform->set_data($mform);
}


base::page(
    new moodle_url('/local/cria/edit_intent.php', ['bot_id' => $INTENT->get_bot_id(), 'id' => $id]),
    get_string('edit_intent', 'local_cria'),
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