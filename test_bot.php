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
use local_cria\gpt;
// CHECK And PREPARE DATA
global $CFG, $OUTPUT, $SESSION, $PAGE, $DB, $COURSE, $USER;

require_login(1, false);
$context = context_system::instance();

$bot_id = required_param('bot_id', PARAM_INT);

\local_cria\base::page(
    $CFG->wwwroot . '/local/cria/test_bot.php',
    get_string('testing_bot', 'local_cria'),
    get_string('testing_bot', 'local_cria'),
    $context);

$PAGE->requires->js_call_amd('local_cria/gpt', 'init');
//**************** ******
//*** DISPLAY HEADER ***
//**********************
echo $OUTPUT->header();

$output = $PAGE->get_renderer('local_cria');
$test_bot = new \local_cria\output\test_bot($bot_id);
echo $output->render_test_bot($test_bot);
//$cache = cache::make('local_cria', 'cria_system_messages');
//echo $cache->get(8 . '_' . sesskey());

//print(gpt::get_response(15, 'What is this bot used for?'));
//**********************
//*** DISPLAY FOOTER ***
//**********************
echo $OUTPUT->footer();