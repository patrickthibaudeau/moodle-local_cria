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

$system_message = 'You are a bot that writes example questions based on a question provided';
$prompt = '[question]How do I apply for university?[/question]';
$prompt .= '[instructions]Write 5 rephrased examples questions based on the content in [question]. return each question in the follwoing JSON format. [{"question": your answer},{"question": your answer}][/instructions]';

//$results  = criadex::query(3,$system_message,$prompt);
//
//$response = $results->response->message->content;
//$usage = $results->response->raw->usage;
//
//print_object($results);
//print_object($response);
//print_object($usage);

$BOT = new bot(53);

$params = json_decode($BOT->get_bot_parameters_json());

$result = criadex::query($params->llm_model_id,$system_message,$prompt, 1024);

//print_object($result);

$json = $result->response->message->content;
print_object(json_decode($json ));
//**********************
//*** DISPLAY FOOTER ***
//**********************
echo $OUTPUT->footer();