<?php
require_once('../../config.php');

// CHECK And PREPARE DATA
global $CFG, $OUTPUT, $SESSION, $PAGE, $DB, $COURSE, $USER;

require_login(1, false);
$context = context_system::instance();

if (!has_capability('local/cria:view_bots', $context)) {
    throw new moodle_exception('nopermissions', 'error', '', 'You do not have permissions to access this page');
}


\local_cria\base::page(
    new moodle_url('/local/cria/bot_config.php'),
    get_string('bot_configurations', 'local_cria'),
    get_string('bot_configurations', 'local_cria'),
    $context);

$PAGE->requires->js_call_amd('local_cria/bot_config', 'init');
//**************** ******
//*** DISPLAY HEADER ***
//**********************
echo $OUTPUT->header();

$output = $PAGE->get_renderer('local_cria');
$dashboard = new \local_cria\output\bot_config();
echo $output->render_bot_config($dashboard);
//**********************
//*** DISPLAY FOOTER ***
//**********************
echo $OUTPUT->footer();