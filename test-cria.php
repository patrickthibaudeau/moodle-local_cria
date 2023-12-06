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
use local_cria\base;

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

$config = get_config('local_cria');
// Get Cria url settings
$criadex_url_setting = $config->criadex_url;
$criabot_url_setting = $config->criabot_url;
// Convert into array
$criadex_params = explode(':', $criadex_url_setting);
$criabot_params = explode(':', $criabot_url_setting);
// Create URL from array
$criadex_url = $criadex_params[0] . ':' . $criadex_params[1];
$criadex_port = $criadex_params[2];
$criabot_url = $criabot_params[0] . ':' . $criabot_params[1];
$criabot_port = $criabot_params[2];


if (base::is_port_open($criadex_url, $criadex_port)) {
    \core\notification::success('Criadex server is running on ' . $criadex_url . ':' . $criadex_port);
} else {
    \core\notification::error('Criadex server is not running on ' . $criadex_url . ':' . $criadex_port);
}


if (base::is_port_open($criabot_url, $criabot_port)) {
    \core\notification::success('Cria bot server is running on ' . $criabot_url . ':' . $criabot_port);
} else {
    \core\notification::error('Cria bot server is not running on ' . $criabot_url . ':' . $criabot_port);
};

//**********************
//*** DISPLAY FOOTER ***
//**********************
echo $OUTPUT->footer();