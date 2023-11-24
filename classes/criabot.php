<?php

namespace local_cria;

use local_cria\gpt;
use local_cria\bot;

class criabot
{

    /****** Bot Management ******/
    /**
     * @param $bot_name
     * @param $data
     * @return mixed
     * @throws \local_cria\dml_exception
     */
    public static function create_bot($bot_name, $data) {
        // Get Config
        $config = get_config('local_cria');
        // Create model
        return gpt::_make_call(
            $config->criabot_url,
            $config->criadex_api_key,
            $data,
            '/bots/'. $bot_name  . '/manage/create',
            'POST'
        );
    }

    /**
     * @param $bot_name
     * @param $data
     * @return void
     */
    public static function update_bot($bot_name, $data) {
        // Get config
        $config = get_config('local_cria');
        // Update model
        return gpt::_make_call(
            $config->criabot_url,
            $config->criadex_api_key,
            $data,
            '/bots/'. $bot_name  . '/manage/params',
            'PATCH'
        );
    }

    /**
     * @param $bot_name
     * @return void
     */
    public static function delete_bot($bot_name) {
        // Get config
        $config = get_config('local_cria');
        // Update model
        return gpt::_make_call(
            $config->criabot_url,
            $config->criadex_api_key,
            [],
            '/bots/'. $bot_name  . '/manage/delete',
            'DELETE'
        );
    }

    /**
     * @param $bot_name
     * @return void
     */
    public static function about_bot($bot_name) {
        // Get config
        $config = get_config('local_cria');
        // Update model
        return gpt::_make_call(
            $config->criabot_url,
            $config->criadex_api_key,
            [],
            '/bots/'. $bot_name  . '/manage/about',
            'GET'
        );
    }

    /******** Document Content ********/
    /**
     * Upload a document associated to the bot
     * @param $bot_name
     * @param $file_path
     * @param $file_name
     * @return void
     */
    public static function create_document($bot_name, $file_path, $file_name) {
        // Get config
        $config = get_config('local_cria');
        // create document
        return gpt::_make_call(
            $config->criabot_url,
            $config->criadex_api_key,
            [],
            '/bots/'. $bot_name  . '/documents/upload',
            'POST',
            $file_path,
            $file_name
        );
    }

    /**
     * Update a document associated to the bot
     * @param $bot_name
     * @param $file_path
     * @param $file_name
     * @return void
     */
    public static function update_document($bot_name, $file_path, $file_name) {
        // Get config
        $config = get_config('local_cria');
        // Update document
        return gpt::_make_call(
            $config->criabot_url,
            $config->criadex_api_key,
            [],
            '/bots/'. $bot_name  . '/documents/update',
            'PATCH',
            $file_path,
            $file_name
        );
    }

    /**
     * Delete a document associated to the bot
     * @param $bot_name
     * @param $file_name
     * @return void
     */
    public static function delete_document($bot_name, $file_name) {
        // Get config
        $config = get_config('local_cria');
        // Prepare date
        $data = [
            'document_name' => $file_name
        ];

        // delete document
        return gpt::_make_call(
            $config->criabot_url,
            $config->criadex_api_key,
            $data,
            '/bots/'. $bot_name  . '/documents/delete',
            'DELETE'
        );
    }

    /**
     * List all documents associated to the bot
     * @param $bot_name
     * @param $file_path
     * @param $file_name
     * @return void
     */
    public static function list_documents($bot_name) {
        // Get config
        $config = get_config('local_cria');
        // List documents
        return gpt::_make_call(
            $config->criabot_url,
            $config->criadex_api_key,
            [],
            '/bots/'. $bot_name  . '/documents/list',
            'GET'
        );
    }

    /******** Question Content ********/
    /**
     * @param $bot_id Int In this case, the bot id is actually an intent id..
     * @param $question_id Int The question id
     * @param $data array [question_examples => []; question_answer => ']
     * @return mixed
     * @throws \local_cria\dml_exception
     */
    public static function create_question($bot_id, $question_id, $data) {
        // Get Config
        $config = get_config('local_cria');

        $bot_name = "$bot_id-$question_id";
        // Create model
        return gpt::_make_call(
            $config->criabot_url,
            $config->criadex_api_key,
            $data,
            '/bots/'. $bot_name  . '/questions/upload',
            'POST'
        );
    }

    /**
     * Update question
     * @param $bot_id Int In this case, the bot id is actually an intent id..
     * @param $question_id Int The question id
     * @param $data array [document_name => '', question_examples => []; question_answer => ']
     * @return mixed
     * @throws \local_cria\dml_exception
     */
    public static function update_question($bot_id, $question_id, $data) {
        // Get Config
        $config = get_config('local_cria');

        $bot_name = "$bot_id-$question_id";
        // Create model
        return gpt::_make_call(
            $config->criabot_url,
            $config->criadex_api_key,
            $data,
            '/bots/'. $bot_name  . '/questions/update',
            'PATCH'
        );
    }

    /**
     * Update question
     * @param $bot_id Int In this case, the bot id is actually an intent id..
     * @param $question_id Int The question id
     * @param $data array [document_name => '']
     * @return mixed
     * @throws \local_cria\dml_exception
     */
    public static function delete_question($bot_id, $question_id, $data) {
        // Get Config
        $config = get_config('local_cria');

        $bot_name = "$bot_id-$question_id";
        // Create model
        return gpt::_make_call(
            $config->criabot_url,
            $config->criadex_api_key,
            $data,
            '/bots/'. $bot_name  . '/questions/delete',
            'PATCH'
        );
    }


    /******** Bot Chats ********/
    /**
     * Start chat session
     * @param $bot_name String
     * @return mixed
     * @throws \local_cria\dml_exception
     */
    public static function chat_start($bot_name) {
        // Get Config
        $config = get_config('local_cria');
        // Create model
        return gpt::_make_call(
            $config->criabot_url,
            $config->criadex_api_key,
            [],
            '/bots/'. $bot_name  . '/chats/start',
            'POST'
        );
    }

    /**
     * Query a bot in a 1-message chat
     * @param $bot_name String
     * @param $prompt String
     * @return mixed
     * @throws \local_cria\dml_exception
     */
    public static function chat_query($bot_name, $prompt) {
        // Get Config
        $config = get_config('local_cria');
        // Create model
        return gpt::_make_call(
            $config->criabot_url,
            $config->criadex_api_key,
            [
                ' prompt' => $prompt
            ],
            '/bots/'. $bot_name  . '/chats/query',
            'POST'
        );
    }

    /**
     * Send a chat to a bot
     * @param $chat_id String
     * @param $prompt String
     * @return mixed
     * @throws \local_cria\dml_exception
     */
    public static function chat_send($chat_id, $prompt) {
        // Get Config
        $config = get_config('local_cria');
        // Create model
        return gpt::_make_call(
            $config->criabot_url,
            $config->criadex_api_key,
            [
                ' prompt' => $prompt
            ],
            '/bots/'. $chat_id  . '/chats/send',
            'POST'
        );
    }

    /**
     * End chat session
     * @param $chat_id String
     * @param $prompt String
     * @return mixed
     * @throws \local_cria\dml_exception
     */
    public static function chat_end($chat_id) {
        // Get Config
        $config = get_config('local_cria');
        // Create model
        return gpt::_make_call(
            $config->criabot_url,
            $config->criadex_api_key,
            [],
            '/bots/'. $chat_id  . '/chats/end',
            'POST'
        );
    }

    /**
     * End chat session
     * @param $chat_id String
     * @param $prompt String
     * @return mixed
     * @throws \local_cria\dml_exception
     */
    public static function chat_history($chat_id) {
        // Get Config
        $config = get_config('local_cria');
        // Create model
        return gpt::_make_call(
            $config->criabot_url,
            $config->criadex_api_key,
            [],
            '/bots/'. $chat_id  . '/chats/history',
            'GET'
        );
    }
}