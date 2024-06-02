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
$criadex_url= $config->criadex_url;
$criabot_url= $config->criabot_url;

// Set bot parameters
$params = '{' .
    '"max_tokens": 5,' .
    '"temperature": 0.5,' .
    '"top_p": 0.5,' .
    '"top_k": 1,' .
    '"min_relevance": 0.1,' .
    '"max_context": 0,' .
    '"no_context_message": "Nothing found",' .
    '"system_message": "you are a bot for testing",' .
    '"llm_model_id": 3,' .
    '"embedding_model_id": 2' .
    '}';

$create_bot = criabot::bot_create('cria-test-from-settings', $params);

if ($create_bot->status == 200) {
    \core\notification::success('Congradulations, Cria backend servers are working!');
    criabot::bot_delete('cria-test-from-settings');
} else {
    \core\notification::error('Cria backend servers are not working! ' . $create_bot->message);
}
//**********************
//*** DISPLAY FOOTER ***
//**********************
echo $OUTPUT->footer();