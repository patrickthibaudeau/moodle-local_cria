<?php
require_once('../../../config.php');

// CHECK And PREPARE DATA
global $CFG, $OUTPUT, $SESSION, $PAGE, $DB, $COURSE, $USER;

require_login(1, false);
$context = context_system::instance();

\local_cria\base::page(
    new moodle_url('/local/cria/minutes_master/index.php'),
    get_string('secondopinion', 'local_cria'),
    get_string('secondopinion', 'local_cria'),
    $context
);

$PAGE->requires->js_call_amd('local_cria/secondopinion', 'init');

//**************** ******
//*** DISPLAY HEADER ***
//**********************
echo $OUTPUT->header();

$output = $PAGE->get_renderer('local_cria');
$dashboard = new \local_cria\output\secondopinion();
echo $output->render_secondopinion($dashboard);
//**********************
//*** DISPLAY FOOTER ***
//**********************
echo $OUTPUT->footer();