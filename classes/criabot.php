<?php

namespace local_cria;

use local_cria\gpt;

class criabot
{
    /****** Bot Management ******/
    /**
     * @param $bot_name
     * @param $data
     * @return mixed
     * @throws \local_cria\dml_exception
     */
    public static function create($bot_name, $data) {
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
    public static function update($bot_name, $data) {
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
    public static function delete($bot_name) {
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
     * @param $model_id
     * @return void
     */
    public static function about($bot_name) {
        // Get config
        $config = get_config('local_cria');
        // Update model
        return gpt::_make_call(
            $config->criabot_url,
            $config->criadex_api_key,
            [], '/bots/'. $bot_name  . '/manage/about',
            'GET'
        );
    }

    /******** Document Content ********/

}