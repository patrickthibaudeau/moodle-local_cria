<?php
require_once('../../config.php');

// CHECK And PREPARE DATA
global $CFG, $OUTPUT, $SESSION, $PAGE, $DB, $COURSE, $USER;

require_login(1, false);

$bot_id = required_param('bot_id', PARAM_INT);

$context = context_system::instance();

$BOT = new \local_cria\bot($bot_id);

\local_cria\base::page(
    new moodle_url('/local/cria/content.php', ['id' => $bot_id]),
    get_string('content', 'local_cria'),
    get_string('content_for', 'local_cria') . ' ' . $BOT->get_name(),
    $context
);

$PAGE->requires->js_call_amd('local_cria/content', 'init');

// Add secondary navigation
include_once('bot_secondaray_nav.php');
//**************** ******
//*** DISPLAY HEADER ***
//**********************
echo $OUTPUT->header();

$output = $PAGE->get_renderer('local_cria');
$content = new \local_cria\output\content($bot_id);
echo $output->render_content($content);
//**********************
//*** DISPLAY FOOTER ***
//**********************
echo $OUTPUT->footer();