<?php
require_once('../../config.php');

use local_cria\cria;
use local_cria\bot;
use local_cria\gpt;

// CHECK And PREPARE DATA
global $CFG, $OUTPUT, $SESSION, $PAGE, $DB, $COURSE, $USER;

require_login(1, false);
$context = context_system::instance();

\local_cria\base::page(
    new moodle_url('/local/cria/testing.php'),
    get_string('pluginname', 'local_cria'),
    'Testing',
    $context
);



//**************** ******
//*** DISPLAY HEADER ***
//**********************
echo $OUTPUT->header();

$file_name = '8a51e01f-eb69-4f3b-9cc5-a8846a004a50';
$bot_id = 33;
$prompt = "Write a network governance policy for York University. Please include that users must use the York University VPN when working remotely.";;
$content = '';

$BOT = new bot($bot_id);

print_object($BOT->get_model_config());
print_object($BOT->get_embedding_config());
//$cache = \cache::make('local_cria', 'cria_system_messages');
//$system_message = $cache->set($BOT->get_bot_type() . '_' . sesskey(), $BOT->get_bot_type_system_message() . ' ' . $BOT->get_bot_system_message());
//


//print_object(cria::start_chat($bot_id));
//print_object(cria::send_chat_request($bot_id, $chat_id, $prompt));

//print_object(cria::delete_file($bot_id, $file_name, true));
//print_object(cria::get_files($bot_id));
//print_object(cria::get_chat_summary($bot_id));


//print_object('Starting chat');
//$message = gpt::get_response($bot_id, $prompt, $content);
//print_object($message);
//print_object(cria::get_files(11));
//**********************
//*** DISPLAY FOOTER ***
//**********************
echo $OUTPUT->footer();