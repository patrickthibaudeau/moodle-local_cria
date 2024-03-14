<?php
require_once('../../config.php');

// CHECK And PREPARE DATA
global $CFG, $OUTPUT, $SESSION, $PAGE, $DB, $COURSE, $USER;

require_login(1, false);
$context = context_system::instance();

$bot_id = required_param('bot_id', PARAM_INT);
$date_range = optional_param('daterange', '', PARAM_TEXT);
if (!$date_range) {
    $start_date = date('m/d/Y', strtotime('first day of this month'));
    $end_date = date('m/d/Y',strtotime('last day of this month'));
    $date_range = $start_date . ' - ' . $end_date;

}
\local_cria\base::page(new moodle_url('/local/cria/bot_logs.php',['id' => $bot_id]), get_string('pluginname', 'local_cria'), '', $context);

$PAGE->requires->jquery();
$PAGE->requires->css(new moodle_url('/local/cria/js/daterangepicker/daterangepicker.js'));
$PAGE->requires->js(new moodle_url('/local/cria/js/daterangepicker/moment.min.js'));
$PAGE->requires->js(new moodle_url('/local/cria/js/daterangepicker/daterangepicker.js'));
$PAGE->requires->js(new moodle_url('/local/cria/js/bot_logs.js'));
//**************** ******
//*** DISPLAY HEADER ***
//**********************
echo $OUTPUT->header();

$output = $PAGE->get_renderer('local_cria');
$logs = new \local_cria\output\bot_logs($bot_id, $date_range);
echo $output->render_bot_logs($logs);
//**********************
//*** DISPLAY FOOTER ***
//**********************
echo $OUTPUT->footer();