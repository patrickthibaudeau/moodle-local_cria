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


require_once('../../config.php');
require_once('lib.php');
require_once("$CFG->dirroot/local/cria/classes/external/gpt.php");
require_once("$CFG->dirroot/local/cria/classes/gpttokenizer/Gpt3TokenizerConfig.php");
require_once("$CFG->dirroot/local/cria/classes/gpttokenizer/Gpt3Tokenizer.php");
require_once("$CFG->dirroot/local/cria/classes/gpttokenizer/Merges.php");
require_once("$CFG->dirroot/local/cria/classes/gpttokenizer/Vocab.php");

use local_cria\datatables;
use local_cria\criadex;
use local_cria\criabot;
use local_cria\criaembed;
use local_cria\gpt;
use local_cria\intent;
use local_cria\bot;

// CHECK And PREPARE DATA
global $CFG, $OUTPUT, $SESSION, $PAGE, $DB, $COURSE, $USER;

require_login(1, false);
$context = context_system::instance();

$bot_id = required_param('bot_id', PARAM_INT);

\local_cria\base::page(
    new moodle_url('/local/cria/testing.php'),
    get_string('pluginname', 'local_cria'),
    'Testing',
    $context
);


//**************** ******
//*** DISPLAY HEADER ***
//**********************
echo $OUTPUT->header();


$BOT = new bot($bot_id);
$intent_id = $BOT->get_default_intent_id();
$json = file_get_contents('/var/www/html/local/cria/output1.json');
// Use this to ket the keys
$content = (array)json_decode($json);

$data = json_decode($json);


foreach ($content as $key => $value) {
    create_questions($intent_id, $key, $data);
}

function create_intent($key, $bot_id, $data)
{
    $INTENT = new \local_cria\intent();
    $params = new stdClass();
    $params->bot_id = $bot_id;
    $params->name = $key;
    $params->published = 1;
    $params->lang = 'en';
    $params->usermodified = 2;

    $id = $INTENT->insert_record($params);
    create_questions($id, $key, $data);
}

function create_questions($intent_id, $key, $data)
{
    global $DB, $USER;
    $INTENT = new \local_cria\intent($intent_id);
    $questions = $data->$key;
    echo $key . '<br>';

    $params = [];
    foreach ($questions as $question) {
        $params[$i]['value'] = $question->examples[0];
        $params[$i]['answer'] = $question->answer;
        $params[$i]['lang'] = 'en';
        $params[$i]['intent_id'] = $intent_id;
        $params[$i]['usermodified'] = 2;
        $params[$i]['generate_answer'] = 0;
        $params[$i]['timemodified'] = time();
        $params[$i]['timecreated'] = time();
        $params[$i]['id'] = $DB->insert_record('local_cria_question', $params[$i]);

        // Update record adding the question id to the parent_id
        $params[$i]['parent_id'] = $params[$i]['id'];
        $DB->update_record('local_cria_question', $params[$i]);
        create_examples($params[$i]['id'], $questions);
//        echo $i . ' for ' . $key . '<br>';
        $i++;
    }

}

function create_examples($question_id, $examples)
{
    global $DB;
    $i = 0;
    $params = [];
//    print_object($examples[0]->examples);
    foreach ($examples[0]->examples as $example) {
        if ($i > 0) {
            $params[$i]['value'] = $example;
            $params[$i]['questionid'] = $question_id;
            $params[$i]['usermodified'] = 2;
            $params[$i]['timemodified'] = time();
            $params[$i]['timecreated'] = time();
            $DB->insert_record('local_cria_question_example', $params[$i]);
        }
        $i++;
    }
}

//print_object($data);
//**********************
//*** DISPLAY FOOTER ***
//**********************
echo $OUTPUT->footer();