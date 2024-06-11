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

require_once($CFG->dirroot . "/local/cria/classes/forms/edit_keyword_form.php");

use local_cria\base;
use local_cria\entity;
use local_cria\keyword;


global $CFG, $OUTPUT, $USER, $PAGE, $DB, $SITE;

$context = CONTEXT_SYSTEM::instance();

require_login(1, false);
//$entity_id = required_param('entity_id', PARAM_INT);÷
$id = optional_param('id', 0, PARAM_INT);
$entity_id = optional_param('entity_id', 0, PARAM_INT);

$KEYWORD = new keyword($id);
if ($id) {
    $ENTITY = new entity($KEYWORD->get_entity_id());
} else {
    $ENTITY = new entity($entity_id);
}

if ($id != 0) {
    $formdata = $DB->get_record('local_cria_keyword', array('id' => $id));
    $formdata->bot_id = $ENTITY->get_bot_id();
    // Get all question examples
    $synonyms = $DB->get_records('local_cria_synonyms', array('keyword_id' => $id));
    $formdata->synonyms = array_values($synonyms);
    $formdata->generate_synonyms = false;

} else {
    $formdata = new stdClass();
    $formdata->id = 0;
    $formdata->entity_id = $ENTITY->get_id();
    $formdata->bot_id = $ENTITY->get_bot_id();
    $formdata->examples = false;
    $formdata->generate_synonyms = 1;
}


// Create form
$mform = new \local_cria\edit_keyword_form(null, array('formdata' => $formdata));
if ($mform->is_cancelled()) {
    //Handle form cancel operation, if cancel button is present on form
    redirect($CFG->wwwroot . '/local/cria/bot_keywords.php?id=' . $formdata->entity_id);
} else if ($data = $mform->get_data()) {
    $data->usermodified = $USER->id;
    $data->timemodified = time();
    if ($data->id == 0) {
        // Insert record
        $data->timecreated = time();
        $data->id = $DB->insert_record('local_cria_keyword', $data);
    } else {
        $DB->update_record('local_cria_keyword', $data);
    }
    if ($data->generate_synonyms == true) {
        $KEYWORD = new keyword($data->id);
        $KEYWORD->create_synoyms();
    }

    // Redirect to content page
    redirect($CFG->wwwroot . '/local/cria/edit_keyword.php?id=' . $data->id . '&entity_id=' . $data->entity_id);
} else {
    // Show form
    $mform->set_data($mform);
}


base::page(
    new moodle_url('/local/cria/edit_keyword.php', ['bot_id' => $ENTITY->get_bot_id(), 'id' => $id]),
    get_string('edit_entity', 'local_cria'),
    '',
    $context,
    'standard'
);

$PAGE->requires->js_call_amd('local_cria/keyword_form', 'init', array());
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