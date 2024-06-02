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
require_once('classes/external/gpt.php');

use local_cria\bot;
use local_cria\criabot;

// CHECK And PREPARE DATA
global $CFG, $OUTPUT, $SESSION, $PAGE, $DB, $COURSE, $USER;

require_login(1, false);
$context = context_system::instance();

$bot_id = required_param('bot_id', PARAM_INT);

\local_cria\base::page(new moodle_url('/local/cria/auto_test.php', ['id' => $bot_id]), get_string('pluginname', 'local_cria'), '', $context);

$PAGE->requires->js_call_amd('local_cria/gpt', 'init');
//**************** ******
//*** DISPLAY HEADER ***
//**********************
echo $OUTPUT->header();

$tests = $DB->get_records('local_cria_qa', ['bot_id' => $bot_id]);

$BOT = new bot($bot_id);

ob_start();
$data = [];
$i = 0;
$chat = criabot::chat_start();
$chat_id = $chat->chat_id;
echo "<table class='table table-striped'>";
echo "<thead>";
echo "<tr>";
echo "<th>Question</th>";
echo "<th>Response from LLM</th>";
echo "<th>Actual Answer</th>";
echo "</tr>";
echo "</thead>";
echo "<tbody>";
foreach ($tests as $test) {
    $response = local_cria_external_gpt::response($bot_id, $chat_id, $test->question, '', '');
    echo "<tr>";
    echo "<td>" . $test->question . "</td>";
    echo "<td>" . $response['message'] . "</td>";
    echo "<td>" . $test->answer . "</td>";
    echo "</tr>";
    // add to data array
    $data[$i]['question'] = $test->question;
    $data[$i]['response'] = $response['message'];
    $data[$i]['answer'] = $test->answer;
    ob_flush();
    flush();
    sleep(2);
    $i++;
}
echo "</tbody>";
echo "</table>";
ob_clean();
$_SESSION['data-' . $bot_id] = json_encode($data);
echo "<a href='auto_test_csv.php?bot_id=" . $bot_id . "' target='_blank' class='btn btn-primary'>Download Excel</a>";
//**********************
//*** DISPLAY FOOTER ***
//**********************
echo $OUTPUT->footer();