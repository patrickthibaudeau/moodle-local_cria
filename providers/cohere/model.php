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

require_once("../../../../config.php");
global $CFG;
require_once($CFG->dirroot . "/local/cria/providers/cohere/model_form.php");

use local_cria\base;
use local_cria\model;
use local_cria\criadex;

global $CFG, $OUTPUT, $USER, $PAGE, $DB, $SITE;

$id = optional_param('id', 0, PARAM_INT);

$context = CONTEXT_SYSTEM::instance();

$MODEL = new model($id);

require_login(1, false);

if ($id) {
    $formdata = $MODEL->get_result();
    $values = json_decode($formdata->value);
    $formdata->api_key = $values->api_key;
    $formdata->api_model = $values->api_model;
} else {
    $formdata = new stdClass();
    $provider = $DB->get_record('local_cria_providers', ['idnumber' => 'cohere']);
    $formdata->provider_id = $provider->id;
}


$mform = new \local_cria\cohere_model_form(null, array('formdata' => $formdata));
if ($mform->is_cancelled()) {
    //Handle form cancel operation, if cancel button is present on form
    redirect($CFG->wwwroot . '/local/cria/bot_models.php');
} else if ($data = $mform->get_data()) {

    $value = new stdClass();
    $value->api_key = $data->api_key;
    $value->api_model = $data->api_model;
    $data->value = json_encode($value);

    unset($data->api_resource);
    unset($data->api_version);
    unset($data->api_key);
    unset($data->api_deployment);
    unset($data->api_model);

    if ($data->id) {
        $data->usermodified = $USER->id;
        $data->timemodified = time();
        $DB->update_record('local_cria_models', $data);
        $id = $data->id;
        $params = $DB->get_record('local_cria_models', ['id' => $id]);
        // Update model on CriaDex
        $results = criadex::update_model($params->criadex_model_id, $data->value, 'cohere');
        if ($results->status == '200') {
            redirect($CFG->wwwroot . '/local/cria/bot_models.php');
        } else {
            // Print the error message
            \core\notification::error($results->status . "\n" . $results->message . "\n" . $results->code);
        }
    } else {
        $data->usermodified = $USER->id;
        $data->timemodified = time();
        $data->timecreated = time();
        $id  = $DB->insert_record('local_cria_models', $data);
        // Create model on CriaDex
        $results = criadex::create_model($data->value, 'cohere');
        if ($results->status == '200') {
            $params = new stdClass();
            $params->id = $id;
            $params->criadex_model_id = $results->model->id;
            $DB->update_record('local_cria_models', $params);
            redirect($CFG->wwwroot . '/local/cria/bot_models.php');
        } else {
            // Print the error message
            \core\notification::error($results->status . "\n" . $results->message . "\n" . $results->code);
        }
    }
} else {

    $mform->set_data($mform);
}


base::page(
    new moodle_url('/local/cria/providers/ms-azure-openai/model.php', ['id' => $id]),
    'MS Azure OpenAI ' . get_string('model', 'local_cria'),
    'MS Azure OpenAI ' . get_string('model', 'local_cria'),
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