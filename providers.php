<?php
require_once('../../config.php');

// CHECK And PREPARE DATA
global $CFG, $OUTPUT, $SESSION, $PAGE, $DB, $COURSE, $USER;

require_login(1, false);
$context = context_system::instance();

if (!has_capability('local/cria:view_providers', $context)) {
    redirect(new moodle_url('/local/cria/index.php'));
}

\local_cria\base::page(
    new moodle_url('/local/cria/bot_config.php'),
    get_string('providers', 'local_cria'),
    get_string('providers', 'local_cria'),
    $context);


//$PAGE->requires->js_call_amd('local_cria/bot_config', 'init');
//**************** ******
//*** DISPLAY HEADER ***
//**********************
echo $OUTPUT->header();

$output = $PAGE->get_renderer('local_cria');
$dashboard = new \local_cria\output\provider_dashboard();
echo $output->render_provider_dashboard($dashboard);
//**********************
//*** DISPLAY FOOTER ***
//**********************
echo $OUTPUT->footer();