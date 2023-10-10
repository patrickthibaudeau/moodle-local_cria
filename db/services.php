<?php
$functions = array(
    'cria_content_delete' => array(
        'classname' => 'local_cria_external_content',
        'methodname' => 'delete',
        'classpath' => 'local/cria/classes/external/content.php',
        'description' => 'Delete content',
        'type' => 'write',
        'capabilities' => '',
        'ajax' => true
    ),
    'cria_bot_delete' => array(
        'classname' => 'local_cria_external_bot',
        'methodname' => 'delete',
        'classpath' => 'local/cria/classes/external/bot.php',
        'description' => 'Delete bot',
        'type' => 'write',
        'capabilities' => '',
        'ajax' => true
    ),
    'cria_bot_type_delete' => array(
        'classname' => 'local_cria_external_bot_type',
        'methodname' => 'delete',
        'classpath' => 'local/cria/classes/external/bot_type.php',
        'description' => 'Delete bot type',
        'type' => 'write',
        'capabilities' => '',
        'ajax' => true
    ),
    'cria_get_gpt_response' => array(
        'classname' => 'local_cria_external_gpt',
        'methodname' => 'response',
        'classpath' => 'local/cria/classes/external/gpt.php',
        'description' => 'Returns response from OpenAI',
        'type' => 'read',
        'capabilities' => '',
        'ajax' => true
    ),
    'cria_get_bot_prompt' => array(
        'classname' => 'local_cria_external_bot',
        'methodname' => 'get_prompt',
        'classpath' => 'local/cria/classes/external/bot.php',
        'description' => 'Get default user prompt for bot',
        'type' => 'read',
        'capabilities' => '',
        'ajax' => true
    ),
    'cria_get_approximate_cost' => array(
        'classname' => 'local_cria_external_tokenizer',
        'methodname' => 'response',
        'classpath' => 'local/cria/classes/external/tokenizer.php',
        'description' => 'Get approximate cost for call to GPT',
        'type' => 'read',
        'capabilities' => '',
        'ajax' => true
    ),
);