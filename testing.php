<?php
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

$bot_id = 85;
$prompt = "Who teaches the course?";
$BOT = new \local_cria\bot($bot_id);

$embed_bot = criaembed::manage_get_config(2);
print_object($embed_bot);
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