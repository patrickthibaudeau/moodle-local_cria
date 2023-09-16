<?php
require_once('../../config.php');

// CHECK And PREPARE DATA
global $CFG, $OUTPUT, $SESSION, $PAGE, $DB, $COURSE, $USER;

require_login(1, false);
$context = context_system::instance();

$bot_id = required_param('id', PARAM_INT);
\local_cria\base::page(new moodle_url('/local/cria/bot_logs.php',['id' => $bot_id]), get_string('pluginname', 'local_cria'), '', $context);

//**************** ******
//*** DISPLAY HEADER ***
//**********************
echo $OUTPUT->header();

$output = $PAGE->get_renderer('local_cria');
$logs = new \local_cria\output\bot_logs($bot_id);
echo $output->render_bot_logs($logs);
//**********************
//*** DISPLAY FOOTER ***
//**********************
echo $OUTPUT->footer();