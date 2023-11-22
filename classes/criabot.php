<?php

namespace local_cria;

use local_cria\gpt;

class criabot
{
    /**
     * @param $data
     * @return mixed
     * @throws \local_cria\dml_exception
     */
    public static function create_bot($data) {
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
    public static function update_model($model_id, $data) {
        // Get config
        $config = get_config('local_cria');
        // Update model
        return gpt::_make_call(
            $config->criadex_url,
            $config->criadex_api_key,
            $data,
            '/azure/models/'. $model_id  . '/update',
            'PATCH'
        );
    }

    /**
     * @param $model_id
     * @return void
     */
    public static function delete_model($model_id) {
        // Get config
        $config = get_config('local_cria');
        // Update model
        return gpt::_make_call(
            $config->criadex_url,
            $config->criadex_api_key,
            [],
            '/azure/models/'. $model_id  . '/delete',
            'DELETE'
        );
    }

    /**
     * @param $model_id
     * @return void
     */
    public static function about_model($model_id) {
        // Get config
        $config = get_config('local_cria');
        // Update model
        return gpt::_make_call(
            $config->criadex_url,
            $config->criadex_api_key,
            [], '/azure/models/'. $model_id  . '/about',
            'GET'
        );
    }

    /**
     * @param $model_id
     * @return void
     */
    public static function query(
        $model_id,
        $system_message,
        $prompt,
        $max_tokens = 500,
        $temperature = 1,
        $top_p = 1
    ) {
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
        // Update model
        return gpt::_make_call(
            $config->criadex_url,
            $config->criadex_api_key,
            json_encode($data),
            '/azure/models/'. $model_id  . '/query',
            'POST'
        );
    }
}