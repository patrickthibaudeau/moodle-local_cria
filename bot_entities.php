<?php
require_once('../../config.php');
use local_cria\bot;
// CHECK And PREPARE DATA
global $CFG, $OUTPUT, $SESSION, $PAGE, $DB, $COURSE, $USER;

require_login(1, false);
$context = context_system::instance();

$bot_id = required_param('bot_id', PARAM_INT);

$BOT = new bot($bot_id);

\local_cria\base::page(
    new moodle_url('/local/cria/bot_config.php'),
    $BOT->get_name(),
    $BOT->get_name(),
    $context);

$PAGE->requires->js('/local/cria/js/datatable_entities.js', true);
//**************** ******
//*** DISPLAY HEADER ***
//**********************
echo $OUTPUT->header();

$output = $PAGE->get_renderer('local_cria');
$entities = new \local_cria\output\bot_entities($bot_id);
echo $output->render_bot_entities($entities);
//**********************
//*** DISPLAY FOOTER ***
//**********************
echo $OUTPUT->footer();