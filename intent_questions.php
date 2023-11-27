<?php
require_once('../../config.php');

// CHECK And PREPARE DATA
global $CFG, $OUTPUT, $SESSION, $PAGE, $DB, $COURSE, $USER;

require_login(1, false);

$bot_id = required_parAM('bot_id', PARAM_INT);
$intent_id = required_parAM('intent_id', PARAM_INT);

$context = context_system::instance();

$INTENT = new \local_cria\intent($intent_id);

\local_cria\base::page(
    new moodle_url('/local/cria/intent_questions.php', ['bot_id' => $bot_id, 'intent_id' => $intent_id]),
    get_string('questions', 'local_cria'),
    get_string('question_for', 'local_cria') . ' ' . $INTENT->get_name(),
    $context
);

//$PAGE->requires->js_call_amd('local_cria/content', 'init');

//**************** ******
//*** DISPLAY HEADER ***
//**********************
echo $OUTPUT->header();

$output = $PAGE->get_renderer('local_cria');
$content = new \local_cria\output\intent_questions($intent_id);
echo $output->render_intent_questions($content);
//**********************
//*** DISPLAY FOOTER ***
//**********************
echo $OUTPUT->footer();