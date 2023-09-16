<?php
require_once('../../config.php');

// CHECK And PREPARE DATA
global $CFG, $OUTPUT, $SESSION, $PAGE, $DB, $COURSE, $USER;

require_login(1, false);

$context = context_system::instance();

\local_cria\base::page(
    new moodle_url('/local/cria/bot_types.php'),
    get_string('bot_types', 'local_cria'),
    get_string('bot_types', 'local_cria'),
    $context
);

$PAGE->requires->js_call_amd('local_cria/bot_type', 'init');

//**************** ******
//*** DISPLAY HEADER ***
//**********************
echo $OUTPUT->header();

$output = $PAGE->get_renderer('local_cria');
$bot_types = new \local_cria\output\bot_types();
echo $output->render_bot_types($bot_types);
//**********************
//*** DISPLAY FOOTER ***
//**********************
echo $OUTPUT->footer();