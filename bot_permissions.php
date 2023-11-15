<?php
require_once('../../config.php');

use local_cria\bot;

// CHECK And PREPARE DATA
global $CFG, $OUTPUT, $SESSION, $PAGE, $DB, $COURSE, $USER;

require_login(1, false);
$context = context_system::instance();

$bot_id = required_param('id', PARAM_INT);

$BOT = new bot($bot_id);

\local_cria\base::page(
    new moodle_url('/local/cria/bot_permissions.php', ['bot_id' => $bot_id]),
    get_string('role', 'local_cria') . ' - ' . $BOT->get_name(),
    get_string('role', 'local_cria') . ' - ' . $BOT->get_name(),
    $context);

$PAGE->requires->js_call_amd('local_cria/bot_role', 'init');
$PAGE->requires->js_call_amd('local_cria/assign_users', 'init');
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