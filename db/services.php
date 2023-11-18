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
    'cria_delete_bot_role' => array(
        'classname' => 'local_cria_external_bot_role',
        'methodname' => 'delete',
        'classpath' => 'local/cria/classes/external/bot_role.php',
        'description' => 'Delete bot role and all permissions associated with it',
        'type' => 'write',
        'capabilities' => '',
        'ajax' => true
    ),
    'cria_assign_user_role' => array(
        'classname' => 'local_cria_external_permission',
        'methodname' => 'assign_user_role',
        'classpath' => 'local/cria/classes/external/permission.php',
        'description' => 'Adds a user to a role',
        'type' => 'write',
        'capabilities' => '',
        'ajax' => true
    ),
    'cria_unassign_user_role' => array(
        'classname' => 'local_cria_external_permission',
        'methodname' => 'unassign_user_role',
        'classpath' => 'local/cria/classes/external/permission.php',
        'description' => 'Removes a user from a role',
        'type' => 'write',
        'capabilities' => '',
        'ajax' => true
    ),
    'cria_get_assigned_users' => array(
        'classname' => 'local_cria_external_permission',
        'methodname' => 'get_assigned_users',
        'classpath' => 'local/cria/classes/external/permission.php',
        'description' => 'Get all users assigned to a role',
        'type' => 'read',
        'capabilities' => '',
        'ajax' => true
    ),
    'cria_get_users' => array(
        'classname' => 'local_cria_external_permission',
        'methodname' => 'get_users',
        'classpath' => 'local/cria/classes/external/permission.php',
        'description' => 'Get all system users',
        'type' => 'read',
        'capabilities' => '',
        'ajax' => true
    ),
    'cria_insert_log' => array(
        'classname' => 'local_cria_external_logs',
        'methodname' => 'insert_log',
        'classpath' => 'local/cria/classes/external/logs.php',
        'description' => 'Insert log record',
        'type' => 'write',
        'capabilities' => '',
        'ajax' => true
    ),
    'cria_create_bot' => array(
        'classname' => 'local_cria_external_bot',
        'methodname' => 'create_bot',
        'classpath' => 'local/cria/classes/external/bot.php',
        'description' => 'Create a new bot',
        'type' => 'write',
        'capabilities' => '',
        'ajax' => true
    ),
    'cria_get_config' => array(
        'classname' => 'local_cria_external_cria',
        'methodname' => 'get_config',
        'classpath' => 'local/cria/classes/external/cria.php',
        'description' => 'Get cria config',
        'type' => 'read',
        'capabilities' => '',
        'ajax' => true
    ),
);