<?php
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