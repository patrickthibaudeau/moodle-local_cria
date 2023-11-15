<?php
require_once('../../config.php');

use local_cria\bot_role;

// CHECK And PREPARE DATA
global $CFG, $OUTPUT, $SESSION, $PAGE, $DB, $COURSE, $USER;

require_login(1, false);
$context = context_system::instance();

$bot_id = required_param('botid', PARAM_INT);
$role_id = required_param('id', PARAM_INT);

$BOT_ROLE = new bot_role($role_id);

if (!has_capability('local/cria:view_bots', $context)) {
    throw new moodle_exception('nopermissions', 'error', '', 'You do not have permissions to access this page');
}


\local_cria\base::page(
    new moodle_url('/local/cria/assign_users.php', ['id' => $role_id,'botid' => $bot_id]),
    get_string('assign_users', 'local_cria') . ' - ' . $BOT_ROLE->get_name(),
    get_string('assign_users', 'local_cria') . ' - ' . $BOT_ROLE->get_name(),
    $context);

$PAGE->requires->js_call_amd('local_cria/assign_users', 'init');
$PAGE->requires->css(new moodle_url('/local/cria/css/select2.min.css'));
$PAGE->requires->css(new moodle_url('/local/cria/css/select2-bootstrap4.min.css'));
//**************** ******
//*** DISPLAY HEADER ***
//**********************
echo $OUTPUT->header();

$output = $PAGE->get_renderer('local_cria');
$dashboard = new \local_cria\output\assign_users($role_id, $bot_id);
echo $output->render_assign_users($dashboard);
//**********************
//*** DISPLAY FOOTER ***
//**********************
echo $OUTPUT->footer();