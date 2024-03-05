<?php

namespace local_cria;

use local_cria\gpt;
use local_cria\bot;

class criabot
{
    /****** Bot Management ******/
    /**
     * @param $bot_name String
     * @param $data JSON String {"max_tokens": 16,
     *  "temperature" => 0.1,
     *  "top_p" => 1,
     *  "top_k" => 10,
     *  "min_relevance" => 0.9,
     *  "max_context" => 2000,
     *  "no_context_message" => "Sorry, I'm not sure about that.",
     *  "system_message" => "string",
     *  "llm_model_id" => 3,
     *  "embedding_model_id" => 4}
     * @return mixed
     * @throws \dml_exception
     */
    public static function bot_create($bot_name, $data) {
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
     * @param $bot_name String
     * @param $data Array ["max_tokens": 16,
     *  "temperature" => 0.1,
     *  "top_p" => 1,
     *  "top_k" => 10,
     *  "min_relevance" => 0.9,
     *  "max_context" => 2000,
     *  "no_context_message" => "Sorry, I'm not sure about that.",
     *  "system_message" => "string",
     *  "llm_model_id" => 3,
     *  "embedding_model_id" => 4]
     * @return void
     */
    public static function bot_update($bot_name, $data) {
        // Get config
        $config = get_config('local_cria');
        // Update model
        return gpt::_make_call(
            $config->criabot_url,
            $config->criadex_api_key,
            $data,
            '/bots/'. $bot_name  . '/manage/update',
            'PATCH'
        );
    }

    /**
     * @param $bot_name String
     * @return void
     */
    public static function bot_delete($bot_name) {
        // Get config
        $config = get_config('local_cria');
        // Update model
        return gpt::_make_call(
            $config->criabot_url,
            $config->criadex_api_key,
            '',
            '/bots/'. $bot_name  . '/manage/delete',
            'DELETE'
        );
    }

    /**
     * @param $bot_name String
     * @return void
     */
    public static function bot_about($bot_name) {
        // Get config
        $config = get_config('local_cria');
        // Update model
        return gpt::_make_call(
            $config->criabot_url,
            $config->criadex_api_key,
            '',
            '/bots/'. $bot_name  . '/manage/about',
            'GET'
        );
    }

    /******** Document Content ********/
    /**
     * Upload a document associated to the bot
     * @param $bot_name String
     * @param $file_path String
     * @param $file_name String
     * @return void
     */
    public static function document_create($bot_name, $file_path, $file_name) {
        // Get config
        $config = get_config('local_cria');
        // create document
        return gpt::_make_call(
            $config->criabot_url,
            $config->criadex_api_key,
            '',
            '/bots/'. $bot_name  . '/documents/upload',
            'POST',
            $file_path,
            $file_name
        );
    }

    /**
     * Update a document associated to the bot
     * @param $bot_name String
     * @param $file_path String
     * @param $file_name String
     * @return void
     */
    public static function document_update($bot_name, $file_path, $file_name) {
        // Get config
        $config = get_config('local_cria');
        // Update document
        return gpt::_make_call(
            $config->criabot_url,
            $config->criadex_api_key,
            '',
            '/bots/'. $bot_name  . '/documents/update',
            'PATCH',
            $file_path,
            $file_name
        );
    }

    /**
     * Delete a document associated to the bot
     * @param $bot_name String
     * @param $file_name String
     * @return void
     */
    public static function document_delete($bot_name, $file_name) {
        // Get config
        $config = get_config('local_cria');
        // Prepare date
        $data = [
            'document_name' => $file_name
        ];
        // To accept spaces in the file name
        $params = http_build_query($data);

        // delete document
        return gpt::_make_call(
            $config->criabot_url ,
            $config->criadex_api_key,
            '',
            '/bots/'. $bot_name  . '/documents/delete/?' . $params,
            'DELETE'
        );
    }

    /**
     * List all documents associated to the bot
     * @param $bot_name String
     * @return void
     */
    public static function document_list($bot_name) {
        // Get config
        $config = get_config('local_cria');
        // List documents
        return gpt::_make_call(
            $config->criabot_url,
            $config->criadex_api_key,
            '',
            '/bots/'. $bot_name  . '/documents/list',
            'GET'
        );
    }

    /******** Question Content ********/
    /**
     * @param $bot_name String SHould be bot_id-intent_id
     * @param $question_id Int The question id
     * @param $data array [question_examples => []; question_answer => ']
     * @return mixed
     * @throws \local_cria\dml_exception
     */
    public static function question_create($bot_name, $data) {
        // Get Config
        $config = get_config('local_cria');
        // Create model
        return gpt::_make_call(
            $config->criabot_url,
            $config->criadex_api_key,
            json_encode($data),
            '/bots/'. $bot_name  . '/questions/upload',
            'POST'
        );
    }

    /**
     * @param $bot_name
     * @param $document_name
     * @param $data
     * @return mixed
     * @throws \dml_exception
     */
    public static function question_update($bot_name, $document_name, $data) {
        // Get Config
        $config = get_config('local_cria');

        // Create model
        return gpt::_make_call(
            $config->criabot_url,
            $config->criadex_api_key,
            json_encode($data),
            '/bots/'. $bot_name  . '/questions/update?document_name=' . $document_name,
            'PATCH'
        );
    }

    /**
     * Update question
     * @param $bot_name String SHould be bot_id-intent_id
     * @param $question_id Int The question id
     * @param $document_name array [document_name => '']
     * @return mixed
     * @throws \local_cria\dml_exception
     */
    public static function question_delete($bot_name, $document_name) {
        // Get Config
        $config = get_config('local_cria');

        // Create model
        return gpt::_make_call(
            $config->criabot_url,
            $config->criadex_api_key,
            [],
            '/bots/'. $bot_name  . '/questions/delete?document_name=' . $document_name,
            'DELETE'
        );
    }

    /**
     * List questions
     * @param $bot_name String SHould be bot_id-intent_id
     * @return mixed
     * @throws \local_cria\dml_exception
     */
    public static function question_list($bot_name) {
        // Get Config
        $config = get_config('local_cria');

        // Create model
        return gpt::_make_call(
            $config->criabot_url,
            $config->criadex_api_key,
            [],
            '/bots/'. $bot_name  . '/questions/list',
            'GET'
        );
    }

    /******** Bot Chats ********/
    /**
     * Start chat session
     * @param $bot_name String
     * @return mixed
     * @throws \local_cria\dml_exception
     */
    public static function chat_start() {
        // Get Config
        $config = get_config('local_cria');
        // Create model
        return gpt::_make_call(
            $config->criabot_url,
            $config->criadex_api_key,
            '',
            '/bots/chats/start',
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
        $data = [
            'prompt' => $prompt
        ];
        // Create model
        return gpt::_make_call(
            $config->criabot_url,
            $config->criadex_api_key,
            json_encode($data),
            '/bots/'. $bot_name  . '/chats/query',
            'POST'
        );
    }

    /**
     * Send a chat to a bot
     * @param $chat_id String
     * @param $bot_name String
     * @param $prompt String
     * @return mixed
     * @throws \local_cria\dml_exception
     */
    public static function chat_send($chat_id, $bot_name, $prompt, $filters = []) {
        // Get Config
        $config = get_config('local_cria');
        // If $filters is empty, create empty array
        if (empty($filters)) {
            $filters = [
                "must" => [],
                "must_not" => [],
                "should" => []
            ];
        }

        $data = [
            'prompt' => $prompt,
            'bot_name' => $bot_name,
            'metadata_filter' => $filters
        ];

        // Create model
        return gpt::_make_call(
            $config->criabot_url,
            $config->criadex_api_key,
            json_encode($data),
            '/bots/chats/'. $chat_id  . '/send',
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
            '',
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
            '',
            '/bots/'. $chat_id  . '/chats/history',
            'GET'
        );
    }

    // Direct query

    /**
     * @param $bot_name
     * @param $prompt
     * @param $filters
     * @return mixed
     * @throws \dml_exception
     */
    public static function query(
        $bot_name,
        $prompt,
        $filters = [],
    )
    {
        // Get config
        $config = get_config('local_cria');
        // If no filters are set, set to empty array
        if (!$filters) {
            $filters = [
                "must" => [],
                "must_not" => [],
                "should" => []
            ];
        }
        // Build data object
        $data = [
            "direct_question_answer" => false,
            "metadata_filter" => $filters,
            "prompt" => $prompt
        ];
        // Update model
        return gpt::_make_call(
            $config->criabot_url,
            $config->criadex_api_key,
            json_encode($data),
            '/bots/' . $bot_name . '/chats/query',
            'POST'
        );
    }
}