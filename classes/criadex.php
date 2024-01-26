<?php

namespace local_cria;

use local_cria\gpt;

class criadex
{
    /**
     * @param $data
     * @return mixed
     * @throws dml_exception
     */
    public static function create_model($data)
    {
        // Get Config
        $config = get_config('local_cria');
        // Create model
        return gpt::_make_call(
            $config->criadex_url,
            $config->criadex_api_key,
            $data,
            '/azure/models/create',
            'POST'
        );
    }

    /**
     * @param $model_id
     * @param $data
     * @return void
     */
    public static function update_model($model_id, $data)
    {
        // Get config
        $config = get_config('local_cria');
        // Update model
        return gpt::_make_call(
            $config->criadex_url,
            $config->criadex_api_key,
            $data,
            '/azure/models/' . $model_id . '/update',
            'PATCH'
        );
    }

    /**
     * @param $model_id
     * @return void
     */
    public static function delete_model($model_id)
    {
        // Get config
        $config = get_config('local_cria');
        // Update model
        return gpt::_make_call(
            $config->criadex_url,
            $config->criadex_api_key,
            [],
            '/azure/models/' . $model_id . '/delete',
            'DELETE'
        );
    }

    /**
     * @param $model_id
     * @return void
     */
    public static function about_model($model_id)
    {
        // Get config
        $config = get_config('local_cria');
        // Update model
        return gpt::_make_call(
            $config->criadex_url,
            $config->criadex_api_key,
            [], '/azure/models/' . $model_id . '/about',
            'GET'
        );
    }

    /**
     * @param $model_id
     * @param $system_message
     * @param $prompt
     * @param $max_tokens int
     * @param $temperature float
     * @param $top_p float
     * @return mixed
     * @throws \dml_exception
     */
    public static function query(
        $model_id,
        $system_message,
        $prompt,
        $max_tokens = 512,
        $temperature = 0.1,
        $top_p = 0.1
    )
    {
        // Get config
        $config = get_config('local_cria');

        // Build data object
        $data = [
            'max_tokens' => $max_tokens,
            'temperature' => $temperature,
            'top_p' => $top_p,
            'history' => [
                [
                    'role' => 'system',
                    'content' => $system_message
                ],
                [
                    'role' => 'user',
                    'content' => $prompt
                ]
            ]
        ];
print_object(json_encode($data));
        // Update model
        return gpt::_make_call(
            $config->criadex_url,
            $config->criadex_api_key,
            json_encode($data),
            '/azure/models/' . $model_id . '/agents/chat',
            'POST'
        );
    }

    /*****************************Intents**********************************/
    /**
     * @param $model_id
     * @param $intents
     * @param $prompt
     * @return void
     */
    public static function get_top_intent($bot_id, $prompt) {
        // Get config
        $config = get_config('local_cria');
        $BOT = new bot($bot_id);
        // Build data object
        $data = [
            'max_tokens' => $BOT->get_max_tokens(),
            'temperature' => $BOT->get_temperature(),
            'top_p' => $BOT->get_top_p(),
            'intents' => $BOT->get_intents(),
            'prompt' => $prompt
        ];

        $model_config = $BOT->get_model_config();

        // Update model
        return gpt::_make_call(
            $config->criadex_url,
            $config->criadex_api_key,
            json_encode($data),
            '/azure/models/' . $model_config->criadex_model_id . '/agents/intents',
            'POST'
        );
    }
}