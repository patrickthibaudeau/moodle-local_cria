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

require_once($CFG->dirroot . "/local/cria/classes/forms/edit_question_form.php");

use local_cria\base;
use local_cria\intent;


global $CFG, $OUTPUT, $USER, $PAGE, $DB, $SITE;

$context = CONTEXT_SYSTEM::instance();

require_login(1, false);
$intent_id = required_param('intent_id', PARAM_INT);
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
    $formdata->keywords = json_decode($formdata->keywords);

    $draftid = file_get_submitted_draft_itemid('answereditor');
    $currentText = file_prepare_draft_area($draftid, $context->id, 'local_cria', 'answer', $formdata->id, base::getEditorOptions($context), $formdata->answer);
    $formdata->answereditor = array('text' => $currentText, 'format' => FORMAT_HTML, 'itemid' => $draftid);

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
    $formdata->generate_answer = 0;
}


// Create form
$mform = new \local_cria\edit_question_form(null, array('formdata' => $formdata));
if ($mform->is_cancelled()) {
    //Handle form cancel operation, if cancel button is present on form
    redirect($CFG->wwwroot . '/local/cria/content.php?bot_id=' . $formdata->bot_id);
} else if ($data = $mform->get_data()) {
    // Convert keywords to JSON
    if (!isset($data->keywords)) {
        $data->keywords = [];
        $keywords = json_encode($data->keywords);
    } else {
        $keywords = '';
    }


    unset($data->keywords);
    $data->keywords = $keywords;
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
        $data->published = 0;
        $DB->update_record('local_cria_question', $data);
    }
    if ($data->create_example_questions == true) {
        $INTENT = new intent($data->intent_id);
        $INTENT->generate_example_questions($data->id);
    }

    //save editor text
    $draftid = file_get_submitted_draft_itemid('answereditor');
    $answerText = file_save_draft_area_files(
        $draftid,
        $context->id,
        'local_cria',
        'answer',
        $data->id,
        base::getEditorOptions($context),
        $data->answereditor['text']
    );
    $data->answer = $answerText;
    // Update record
    $DB->set_field('local_cria_question', 'answer', $data->answer, array('id' => $data->id));

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

$PAGE->requires->css(new moodle_url('/local/cria/css/select2.min.css'));
$PAGE->requires->css(new moodle_url('/local/cria/css/select2-bootstrap4.min.css'));
$PAGE->requires->js_call_amd('local_cria/question_form', 'init', array());
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