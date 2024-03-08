<?php
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