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

// CHECK And PREPARE DATA
global $CFG, $OUTPUT, $SESSION, $PAGE, $DB, $COURSE, $USER;

require_login(1, false);
$context = context_system::instance();

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

$bot_id = 55;
$prompt = "WHo teaches the course?";
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

$results = json_decode(file_get_contents('/var/www/moodledata/temp/result.json'));
print_object($results);
//$intents_result = criadex::get_top_intent($bot_id, $prompt);
//print_object($intents_result);
//$result = criabot::chat_send( 'fa1602a6-91d8-490b-aff2-6ce68342b454', $prompt, [], true );
//print_object($result);
//**********************
//*** DISPLAY FOOTER ***
//**********************
echo $OUTPUT->footer();