<?php
require_once('../../config.php');
require_once('lib.php');
require_once("$CFG->dirroot/local/cria/classes/external/gpt.php");
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
use local_cria\base;
use local_cria\intent;

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

$bot_id = 1;
$INTENT = new intent($bot_id);

$INTENT->publish_question(1);



//**********************
//*** DISPLAY FOOTER ***
//**********************
echo $OUTPUT->footer();