<?php
require_once('../../config.php');

// CHECK And PREPARE DATA
global $CFG, $OUTPUT, $SESSION, $PAGE, $DB, $COURSE, $USER;

require_login(1, false);

$context = context_system::instance();

\local_cria\base::page(
    new moodle_url('/local/cria/bot_models.php'),
    get_string('bot_models', 'local_cria'),
    get_string('bot_models', 'local_cria'),
    $context
);

$PAGE->requires->js_call_amd('local_cria/bot_models', 'init');

//**************** ******
//*** DISPLAY HEADER ***
//**********************
echo $OUTPUT->header();

$output = $PAGE->get_renderer('local_cria');
$bot_models = new \local_cria\output\bot_models();
echo $output->render_bot_models($bot_models);
//**********************
//*** DISPLAY FOOTER ***
//**********************
echo $OUTPUT->footer();