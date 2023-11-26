<?php
require_once('../../config.php');
require_once('lib.php');
require_once("$CFG->dirroot/local/cria/classes/gpttokenizer/Gpt3TokenizerConfig.php");
require_once("$CFG->dirroot/local/cria/classes/gpttokenizer/Gpt3Tokenizer.php");
require_once("$CFG->dirroot/local/cria/classes/gpttokenizer/Merges.php");
require_once("$CFG->dirroot/local/cria/classes/gpttokenizer/Vocab.php");

use local_cria\gpt;
use local_cria\Gpt3Tokenizer;
use local_cria\Gpt3TokenizerConfig;
use local_cria\bot_role;
use local_cria\criadex;
use local_cria\conversation_styles;
use local_cria\bot;
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

$system_message = 'You are a pmath wiz.';
$prompt = 'What is 2 + 2?';

//$results  = criadex::query(3,$system_message,$prompt);
//
//$response = $results->response->message->content;
//$usage = $results->response->raw->usage;
//
//print_object($results);
//print_object($response);
//print_object($usage);

$BOT = new bot(53);
$params = $BOT->get_bot_parameters_json();


print_object(criabot::document_create('53', '/var/www/moodledata/temp/cria/53/cody_-_admissions_final_1.docx','cody_-_admissions_final_1.docx'));

//**********************
//*** DISPLAY FOOTER ***
//**********************
echo $OUTPUT->footer();