<?php
require_once('../../../config.php');

// CHECK And PREPARE DATA
global $CFG, $OUTPUT, $SESSION, $PAGE, $DB, $COURSE, $USER;

require_login(1, false);
$context = context_system::instance();

\local_cria\base::page(
    new moodle_url('/local/cria/minutes_master/index.php'),
    get_string('translation_app', 'local_cria'),
    get_string('translation_app', 'local_cria'),
    $context
);

$PAGE->requires->js_call_amd('local_cria/translate', 'init');

//**************** ******
//*** DISPLAY HEADER ***
//**********************
echo $OUTPUT->header();

$output = $PAGE->get_renderer('local_cria');
$dashboard = new \local_cria\output\translate();
echo $output->render_translate($dashboard);
//**********************
//*** DISPLAY FOOTER ***
//**********************
echo $OUTPUT->footer();