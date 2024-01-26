<?php

require_once("../../config.php");

require_once($CFG->dirroot . "/local/cria/classes/forms/edit_bot_form.php");


use local_cria\base;
use local_cria\bot;


global $CFG, $OUTPUT, $USER, $PAGE, $DB, $SITE;

$id = optional_param('bot_id', 0, PARAM_INT);
$return = optional_param('return', 'bot_config', PARAM_TEXT);

$context = CONTEXT_SYSTEM::instance();

require_login(1, false);

if ($id) {
    $BOT = new bot($id);
    $formdata = $BOT->get_record();
    $formdata->bot_id = $id;
    $formdata->description_editor['text'] = $formdata->description;
    $formdata->welcome_message_editor['text'] = $formdata->welcome_message;
    $formdata->bot_max_tokens = $BOT->get_model_max_tokens();
    $formdata->child_bots = json_decode($formdata->child_bots);

} else {
    $formdata = new stdClass();
    $formdata->model_id = false;
    $formdata->requires_user_prompt = 1;
    $formdata->requires_content_prompt = 1;
    $formdata->temperature = 0.1;
    $formdata->top_p = 0.1;
    $formdata->top_k = 0.1;
    $formdata->min_relevance = 0.8;
    $formdata->theme_color = '#e31837';
    $formdata->max_context = 50;
    $formdata->no_context_use_message = 1;
    $formdata->no_context_llm_guess = 0;
}
$formdata->return = $return;

$mform = new \local_cria\edit_bot_form(null, array('formdata' => $formdata));
if ($mform->is_cancelled()) {
    //Handle form cancel operation, if cancel button is present on form
    redirect($CFG->wwwroot . '/local/cria/' . $return . '.php?bot_id=' . $id);
} else if ($data = $mform->get_data()) {
    $return = $data->return;
    unset($data->return);
    if ($data->id) {

        $BOT = new bot($data->id);
        $data->description = $data->description_editor['text'];
        $BOT->update_record($data);
        // Unset existing bot object
        unset($BOT);
        // Create new bot object so that new parmaeters can be used.
        $UPDATED_BOT = new bot($data->id);
        $UPDATED_BOT->update_bot_on_bot_server($UPDATED_BOT->get_default_intent_id());
    } else {
        $data->description = $data->description_editor['text'];
        $BOT = new bot();
        $id = $BOT->insert_record($data);
        // Unset existing bot object
        unset($bot);
    }


    redirect($CFG->wwwroot . '/local/cria/' . $return . '.php?bot_id=' . $data->id);
} else {
    $mform->set_data($mform);
}

base::page(
    new moodle_url('/local/cria/edit_bot.php', ['bot_id' => $id]),
    get_string('bot', 'local_cria'),
    '',
    $context,
    'standard'
);
$PAGE->requires->js_call_amd('local_cria/bot_form', 'init');
$PAGE->requires->js(new moodle_url('/local/cria/js/jscolor.js'));
$PAGE->requires->css(new moodle_url('/local/cria/css/select2.min.css'));
$PAGE->requires->css(new moodle_url('/local/cria/css/select2-bootstrap4.min.css'));

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