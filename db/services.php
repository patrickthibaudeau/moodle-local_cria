<?php

/**
* This file is part of Cria.
* Cria is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.
* Cria is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
* You should have received a copy of the GNU General Public License along with Cria. If not, see <https://www.gnu.org/licenses/>.
*
* @package    local_cria
* @author     Patrick Thibaudeau
* @copyright  2024 onwards York University (https://yorku.ca)
* @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
*/


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
    'cria_content_publish_urls' => array(
        'classname' => 'local_cria_external_content',
        'methodname' => 'publish_urls',
        'classpath' => 'local/cria/classes/external/content.php',
        'description' => 'Add Web Page',
        'type' => 'write',
        'capabilities' => '',
        'ajax' => true
    ),
    'cria_content_publish_files' => array(
        'classname' => 'local_cria_external_content',
        'methodname' => 'publish_files',
        'classpath' => 'local/cria/classes/external/content.php',
        'description' => 'Vectorize all files to indexing server',
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
    'cria_get_bot_type_message' => array(
        'classname' => 'local_cria_external_bot_type',
        'methodname' => 'get_system_message',
        'classpath' => 'local/cria/classes/external/bot_type.php',
        'description' => 'Returns bot type system message',
        'type' => 'read',
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
    'cria_get_model_max_tokens' => array(
        'classname' => 'local_cria_external_models',
        'methodname' => 'get_max_tokens',
        'classpath' => 'local/cria/classes/external/models.php',
        'description' => 'Get max tokens',
        'type' => 'read',
        'capabilities' => '',
        'ajax' => true
    ),
    'cria_get_answer' => array(
        'classname' => 'local_cria_external_question',
        'methodname' => 'get_answer',
        'classpath' => 'local/cria/classes/external/question.php',
        'description' => 'Returns answer based on question id',
        'type' => 'read',
        'capabilities' => '',
        'ajax' => true
    ),
    'cria_question_delete' => array(
        'classname' => 'local_cria_external_question',
        'methodname' => 'delete',
        'classpath' => 'local/cria/classes/external/question.php',
        'description' => 'Deletes question and all examples',
        'type' => 'write',
        'capabilities' => '',
        'ajax' => true
    ),
    'cria_question_delete_all' => array(
        'classname' => 'local_cria_external_question',
        'methodname' => 'delete_all',
        'classpath' => 'local/cria/classes/external/question.php',
        'description' => 'Deletes all questions for an intent',
        'type' => 'write',
        'capabilities' => '',
        'ajax' => true
    ),
    'cria_question_publish' => array(
        'classname' => 'local_cria_external_question',
        'methodname' => 'publish',
        'classpath' => 'local/cria/classes/external/question.php',
        'description' => 'Publish a question to criabot',
        'type' => 'write',
        'capabilities' => '',
        'ajax' => true
    ),
    'cria_question_example_update' => array(
        'classname' => 'local_cria_external_question',
        'methodname' => 'update_example',
        'classpath' => 'local/cria/classes/external/question.php',
        'description' => 'Update example question',
        'type' => 'write',
        'capabilities' => '',
        'ajax' => true
    ),
    'cria_question_example_delete' => array(
        'classname' => 'local_cria_external_question',
        'methodname' => 'delete_example',
        'classpath' => 'local/cria/classes/external/question.php',
        'description' => 'Delete example question',
        'type' => 'write',
        'capabilities' => '',
        'ajax' => true
    ),
    'cria_question_example_insert' => array(
        'classname' => 'local_cria_external_question',
        'methodname' => 'insert_example',
        'classpath' => 'local/cria/classes/external/question.php',
        'description' => 'Add example question',
        'type' => 'write',
        'capabilities' => '',
        'ajax' => true
    ),
    // Synonyms
    'cria_synonym_update' => array(
        'classname' => 'local_cria_external_synonym',
        'methodname' => 'update',
        'classpath' => 'local/cria/classes/external/synonym.php',
        'description' => 'Update synonym',
        'type' => 'write',
        'capabilities' => '',
        'ajax' => true
    ),
    'cria_synonym_delete' => array(
        'classname' => 'local_cria_external_synonym',
        'methodname' => 'delete',
        'classpath' => 'local/cria/classes/external/synonym.php',
        'description' => 'Delete synonym',
        'type' => 'write',
        'capabilities' => '',
        'ajax' => true
    ),
    'cria_synonym_insert' => array(
        'classname' => 'local_cria_external_synonym',
        'methodname' => 'insert',
        'classpath' => 'local/cria/classes/external/synonym.php',
        'description' => 'Add synonym',
        'type' => 'write',
        'capabilities' => '',
        'ajax' => true
    ),
    // Chatting
    'cria_get_chat_id' => array(
        'classname' => 'local_cria_external_criabot',
        'methodname' => 'chat_start',
        'classpath' => 'local/cria/classes/external/criabot.php',
        'description' => 'Starts a chat session and returns the chat id',
        'type' => 'read',
        'capabilities' => '',
        'ajax' => true
    ),
    'cria_bot_exists' => array(
        'classname' => 'local_cria_external_criabot',
        'methodname' => 'bot_exists',
        'classpath' => 'local/cria/classes/external/criabot.php',
        'description' => 'Check to see if a bot exists',
        'type' => 'read',
        'capabilities' => '',
        'ajax' => true
    ),
);