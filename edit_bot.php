<?php

/**
* This file is part of Cria.
* Cria is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.
* Cria is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
* You should have received a copy of the GNU General Public License along with Cria. If not, see <https://www.gnu.org/licenses/>.
*
* @package    local_cria
* @author     Patrick Thibaudeau
* @copyright  2024 onwards York University (https://yorku.ca)
* @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
*/



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
    $formdata->bot_api_key = $BOT->get_bot_api_key();
    $formdata->bot_name = $BOT->get_bot_name();
    $draftitemid = file_get_submitted_draft_itemid('icon_url');
    file_prepare_draft_area(
    // The $draftitemid is the target location.
        $draftitemid,
        $context->id,
        'local_cria',
        'bot_icon',
        $formdata->id,
        [
            'subdirs' => 0,
            'maxbytes' => 0,
            'maxfiles' => 1
        ]
    );
    $formdata->icon_url = $draftitemid;
} else {
    $formdata = new stdClass();
    $formdata->id = $id;
    $formdata->model_id = false;
    $formdata->requires_user_prompt = 1;
    $formdata->requires_content_prompt = 0;
    $formdata->temperature = 0.9;
    $formdata->top_p = 0.1;
    $formdata->top_k = '10';
    $formdata->top_n = 3;
    $formdata->min_k = 0.8;
    $formdata->min_relevance = 0.7; //min_n
    $formdata->theme_color = '#e31837';
    $formdata->fine_tuning = true;
    $formdata->max_context = 1024; //max_input_tokens
    $formdata->no_context_use_message = 1;
    $formdata->no_context_llm_guess = 0;
    $formdata->rerank_modle_id = 1;
    $formdata->botwatermark = 0;
    $formdata->parse_strategy = 'GENERIC';
}
$formdata->return = $return;

$mform = new \local_cria\edit_bot_form(null, array('formdata' => $formdata));
if ($mform->is_cancelled()) {
    //Handle form cancel operation, if cancel button is present on form
    redirect($CFG->wwwroot . '/local/cria/' . $return . '.php?bot_id=' . $id);
} else if ($data = $mform->get_data()) {
    $return = $data->return;
    unset($data->return);
    // unset bot_api_key and bot_name
    unset($data->bot_api_key);
    unset($data->bot_name);

    if ($data->id) {
        $id = $data->id;
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

    file_save_draft_area_files(
    // The $data->attachments property contains the itemid of the draft file area.
        $data->icon_url,
        $context->id,
        'local_cria',
        'bot_icon',
        $id,
        [
            'subdirs' => 0,
            'maxbytes' => 1000000,
            'maxfiles' => 1
        ]
    );

    if ($data->id) {
        redirect($CFG->wwwroot . '/local/cria/' . $return . '.php?bot_id=' . $data->id);
    } else {
        redirect($CFG->wwwroot . '/local/cria/edit_bot.php?bot_id=' . $id);
    }
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