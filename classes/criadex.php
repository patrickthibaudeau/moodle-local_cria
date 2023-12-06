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
     * @param $max_tokens
     * @param $temperature
     * @param $top_p
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
        // Update model
        return gpt::_make_call(
            $config->criadex_url,
            $config->criadex_api_key,
            json_encode($data),
            '/azure/models/' . $model_id . '/query',
            'POST'
        );
    }

    // Index management

    /**
     * @param $index_name
     * @param $model_id
     * @param $embedding_model_id
     * @param $type DOCUMENT or QUESTION
     * @return mixed
     * @throws \dml_exception
     */
    public static function index_create($index_name, $model_id, $embedding_model_id, $type = 'DOCUMENT')
    {
        // Get config
        $config = get_config('local_cria');
        $data = '{
            "type": "' . $type . '",' .
            '"llm_model_id": ' . $model_id . ',' .
            '"embedding_model_id": ' . $embedding_model_id . '}';
        // Update model
        print_object(json_decode($data));
        return gpt::_make_call(
            $config->criadex_url,
            $config->criadex_api_key,
            $data,
            '/criadex/' . $index_name . '/create',
            'POST'
        );
    }

    /**
     * @param $index_name
     * @return mixed
     * @throws \dml_exception
     */
    public static function index_about($index_name)
    {
        // Get config
        $config = get_config('local_cria');
        // Update model
        return gpt::_make_call(
            $config->criadex_url,
            $config->criadex_api_key,
            [],
            '/criadex/' . $index_name . '/about',
            'GET'
        );
    }

    /**
     * @param $index_name string
     * @param $api_key string
     * @return mixed
     * @throws \dml_exception
     */
    public static function index_authorization_create($index_name, $api_key)
    {
        // Get config
        $config = get_config('local_cria');

        // Update model
        return gpt::_make_call(
            $config->criadex_url,
            $config->criadex_api_key,
            '',
            '/index_auth/' . $index_name . '/create?api_key=' . $api_key,
            'POST'
        );
    }

    /**
     * @param $index_name string
     * @param $api_key string
     * @return mixed
     * @throws \dml_exception
     */
    public static function index_authorization_check($index_name, $api_key)
    {
        // Get config
        $config = get_config('local_cria');

        // Update model
        return gpt::_make_call(
            $config->criadex_url,
            $config->criadex_api_key,
            '',
            '/index_auth/' . $index_name . '/check?api_key=' . $api_key,
            'GET'
        );
    }

    /**
     * @param $index_name string
     * @param $api_key string
     * @return mixed
     * @throws \dml_exception
     */
    public static function index_authorization_delete($index_name, $api_key)
    {
        // Get config
        $config = get_config('local_cria');

        // Update model
        return gpt::_make_call(
            $config->criadex_url,
            $config->criadex_api_key,
            '',
            '/index_auth/' . $index_name . '/delete?api_key=' . $api_key,
            'DELETE'
        );
    }

    /**
     * @param $index_name string
     * @param $api_key string
     * @return mixed
     * @throws \dml_exception
     */
    public static function index_authorization_list($api_key)
    {
        // Get config
        $config = get_config('local_cria');

        // Update model
        return gpt::_make_call(
            $config->criadex_url,
            $config->criadex_api_key,
            '',
            '/index_auth/' . $api_key . '/list',
            'GET'
        );
    }
}