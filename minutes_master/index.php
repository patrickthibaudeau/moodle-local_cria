<?php
require_once('../../../config.php');

// CHECK And PREPARE DATA
global $CFG, $OUTPUT, $SESSION, $PAGE, $DB, $COURSE, $USER;

require_login(1, false);
$context = context_system::instance();

\local_cria\base::page(
    new moodle_url('/local/cria/minutes_master/index.php'),
    get_string('minutes_master', 'local_cria'),
    get_string('minutes_master', 'local_cria'),
    $context
);

$PAGE->requires->js_call_amd('local_cria/minutes_master', 'init');

//**************** ******
//*** DISPLAY HEADER ***
//**********************
echo $OUTPUT->header();

$output = $PAGE->get_renderer('local_cria');
$dashboard = new \local_cria\output\minutes_master();
echo $output->render_minutes_master($dashboard);
//**********************
//*** DISPLAY FOOTER ***
//**********************
echo $OUTPUT->footer();