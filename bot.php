<?php

require_once("../../config.php");

require_once($CFG->dirroot . "/local/cria/classes/forms/bot_form.php");


use local_cria\base;
use local_cria\bot;


global $CFG, $OUTPUT, $USER, $PAGE, $DB, $SITE;

$id = optional_param('id', 0, PARAM_INT);
$return = optional_param('return', '', PARAM_INT);

$context = CONTEXT_SYSTEM::instance();

require_login(1, false);

if ($id) {
    $BOT = new bot($id);
    $formdata = $BOT->get_record();
    $formdata->description_editor['text'] = $formdata->description;
    $formdata->welcome_message_editor['text'] = $formdata->welcome_message;
    $formdata->bot_max_tokens = $BOT->get_model_max_tokens();

} else {
    $formdata = new stdClass();
    $formdata->model_id = false;
    $formdata->requires_user_prompt = 1;
    $formdata->requires_content_prompt = 1;
    $formdata->temperature = 0.1;
    $formdata->top_p = 0.1;
    $formdata->top_k = 0.1;
    $formdata->minimum_relevance = 0.3;
    $formdata->theme_color = '#e31837';
    $formdata->max_context = 50;
}

$mform = new \local_cria\bot_form(null, array('formdata' => $formdata));
if ($mform->is_cancelled()) {
    //Handle form cancel operation, if cancel button is present on form
    redirect($CFG->wwwroot . '/local/cria/bot_config.php');
} else if ($data = $mform->get_data()) {

    if ($data->id) {
        $BOT = new bot($data->id);
        $data->description = $data->description_editor['text'];
        $BOT->update_record($data);
        // Unset existing bot object
        unset($BOT);
        // Create new bot object so that new parmaeters can be used.
        $UPDATED_BOT = new bot($data->id);
        if ($UPDATED_BOT->use_bot_server()) {
            $UPDATED_BOT->update_bot_on_bot_server($UPDATED_BOT->get_default_intent_id());
        }

    } else {
        $data->description = $data->description_editor['text'];
        $BOT = new bot();
        $id = $BOT->insert_record($data);
        // Unset existing bot object
        unset($bot);
    }


    redirect($CFG->wwwroot . '/local/cria/bot_config.php');


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
$PAGE->requires->js_call_amd('local_cria/bot_form', 'init');
$PAGE->requires->js(new moodle_url('/local/cria/js/jscolor.js'));

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