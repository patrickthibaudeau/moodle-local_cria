<?php
/**
 * Build secondary menu
 * NOT YET USED BUT WILL BE USED IN THE FUTURE
 */
global $PAGE;

$bot_id = required_param('bot_id', PARAM_INT);
$context = context_system::instance();
//$PAGE->requires->css(new moodle_url('/local/yulearn/css/admin_navigation.css'));
$PAGE->secondarynav->add(
    get_string('content', 'local_cria'),
    new moodle_url('/local/cria/content.php?bot_id=' . $bot_id)
);
$PAGE->secondarynav->add(
    get_string('entities', 'local_cria'),
    new moodle_url('/local/cria/bot_entities.php?bot_id=' . $bot_id)
);
$PAGE->secondarynav->add(
    get_string('edit_bot', 'local_cria'),
    new moodle_url('/local/cria/edit_bot.php?bot_id=' . $bot_id)
);
$PAGE->secondarynav->add(
    get_string('test_bot', 'local_cria'),
    new moodle_url('/local/cria/test_bot.php?bot_id=' . $bot_id)
);
$PAGE->secondarynav->add(
    get_string('logs', 'local_cria'),
    new moodle_url('/local/cria/bot_logs.php?bot_id=' . $bot_id)
);
