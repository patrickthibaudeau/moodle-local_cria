<?php
require_once('../../config.php');

use local_cria\bot;

// CHECK And PREPARE DATA
global $CFG, $OUTPUT, $SESSION, $PAGE, $DB, $COURSE, $USER;

require_login(1, false);
$context = context_system::instance();

$bot_id = required_param('id', PARAM_INT);

$BOT = new bot($bot_id);

if (!has_capability('local/cria:view_bots', $context)) {
    throw new moodle_exception('nopermissions', 'error', '', 'You do not have permissions to access this page');
}


\local_cria\base::page(
    new moodle_url('/local/cria/bot_permissions.php', ['bot_id' => $bot_id]),
    get_string('role', 'local_cria') . ' - ' . $BOT->get_name(),
    get_string('role', 'local_cria') . ' - ' . $BOT->get_name(),
    $context);

$PAGE->requires->js_call_amd('local_cria/bot_role', 'init');
//**************** ******
//*** DISPLAY HEADER ***
//**********************
echo $OUTPUT->header();

$output = $PAGE->get_renderer('local_cria');
$dashboard = new \local_cria\output\bot_permissions($bot_id);
echo $output->render_bot_permissions($dashboard);
//**********************
//*** DISPLAY FOOTER ***
//**********************
echo $OUTPUT->footer();