<?php

/**
* This file is part of Crai.
* Cria is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.
* Crai is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
* You should have received a copy of the GNU General Public License along with Crai. If not, see <https://www.gnu.org/licenses/>.
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

use local_cria\base;
use local_cria\datatables;
use local_cria\criadex;
use local_cria\criabot;
use local_cria\criaembed;
use local_cria\gpt;
use local_cria\intent;
use local_cria\questions;
use local_cria\files;
use local_cria\file;
use local_cria\bot;
use local_cria\criaparse;

// CHECK And PREPARE DATA
global $CFG, $OUTPUT, $SESSION, $PAGE, $DB, $COURSE, $USER;

require_login(1, false);
$context = context_system::instance();

$prompt = optional_param('prompt', '', PARAM_TEXT);

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

//print_object(base::get_parsing_strategies());
//$PARSER = new \local_cria\criaparse();
//print_object($PARSER->get_strategies());
//$results = $PARSER->execute('PARAGRAPH', '/var/www/moodledata/temp/wu.docx');
//print_object($results);
//file_put_contents('/var/www/moodledata/temp/wu.json', json_encode($results, JSON_PRETTY_PRINT));
$FILE = new file();
$path = '/var/www/moodledata/temp/';
$filename = 'a_test.pdf';

$FILE->convert_pdf_to_docx($path, $filename);
die;

$bot_id = 1;
$content = file_get_contents('/var/www/html/local/cria/test_minutes.txt');
$BOT = new \local_cria\bot($bot_id);

$bot_params = json_decode($BOT->get_bot_parameters_json());

$content = str_replace("\n", ' ', $content);


$full_prompt = $content . ' Q: Create meeting notes from the context provided and separate the notes by topic. Each topic should be in a 
        numbered list. Once done, create all action items from the context. Format the action items as a list having 
        the following headings: Assigned to, Description, Date due';

//$test = criadex::query(
//    $bot_params->llm_model_id,
//    $bot_params->system_message,
//    $full_prompt,
//    $bot_params->max_tokens,
//    $bot_params->temperature,
//    $bot_params->top_p
//);
//
//print_object($test);
//die;

$result = gpt::get_response($bot_id, $full_prompt, $content, false);

print_object($result);
//$INTENT = new \local_cria\intent(2);
//$INTENT->create_example_questions(11);
//$BOT->delete_record();

//$session = criabot::chat_start();
//$chat_id = $session->chat_id;
//print_object($chat_id);
//$results = local_cria_external_gpt::response($bot_id,$chat_id,$prompt, '', '');
//print_object($results);

//$savy = file_get_contents('/var/www/html/local/cria/SAVY_entities_Keywords.json');
////
//$entities = json_decode( $savy );
//
//
////print_object($entities);
//
//foreach($entities as $entity) {
//    $entity->bot_id = $bot_id;
//    $entity->name = $entity->entity;
//    $entity->usermodified = $USER->id;
//    $entity->timemodified = time();
//    $entity->timecreated = time();
//    $entity_id = $DB->insert_record('local_cria_entity', $entity);
//    // Loop through all values and insert into the keyword table
//    foreach($entity->values as $value) {
//        $keyword = new \stdClass();
//        $keyword->entity_id = $entity_id;
//        $keyword->value = $value->value;
//        $keyword->usermodified = $USER->id;
//        $keyword->timemodified = time();
//        $keyword->timecreated = time();
//        $keyword_id = $DB->insert_record('local_cria_keyword', (array)$keyword);
//        // Loop through all synonyms and insert into the synonym table
//        foreach($value->synonyms as $key => $synonym) {
//            $syn = new \stdClass();
//            $syn->keyword_id = $keyword_id;
//            $syn->value = $synonym;
//            $syn->usermodified = $USER->id;
//            $syn->timemodified = time();
//            $syn->timecreated = time();
//            $syn_id = $DB->insert_record('local_cria_synonyms', (array)$syn);
//        }
//    }
//}


//$intents_result = criadex::get_top_intent($bot_id, $prompt);
//print_object($intents_result);
//$session = criabot::chat_start($BOT->get_bot_name());
//$chat_id = $session->chat_id;
//print_object($chat_id);
//$result = criabot::chat_send( $chat_id, $prompt, [], true );
//print_object($result);

//$json = file_get_contents('/var/www/moodledata/temp/chunk_result_0.json');
//$content = json_decode($json);
//$chunks = gpt::_split_into_chunks(55, $content);
//print_object($content);
//**********************
//*** DISPLAY FOOTER ***
//**********************
echo $OUTPUT->footer();