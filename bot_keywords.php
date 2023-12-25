<?php
require_once('../../config.php');
use local_cria\bot;
use local_cria\entity;
// CHECK And PREPARE DATA
global $CFG, $OUTPUT, $SESSION, $PAGE, $DB, $COURSE, $USER;

require_login(1, false);
$context = context_system::instance();

$entity_id = required_param('entity_id', PARAM_INT);

$ENTITY = new entity($entity_id);
$BOT = new bot($ENTITY->get_bot_id());

\local_cria\base::page(
    new moodle_url('/local/cria/bot_config.php'),
    $BOT->get_name() . ' - ' . $ENTITY->get_name(),
    $BOT->get_name() . ' - ' . $ENTITY->get_name(),
    $context);

$PAGE->requires->js('/local/cria/js/datatable_keywords.js', true);
//**************** ******
//*** DISPLAY HEADER ***
//**********************
echo $OUTPUT->header();

$output = $PAGE->get_renderer('local_cria');
$keywords = new \local_cria\output\bot_keywords($entity_id, $ENTITY->get_bot_id());
echo $output->render_bot_keywords($keywords);
//**********************
//*** DISPLAY FOOTER ***
//**********************
echo $OUTPUT->footer();