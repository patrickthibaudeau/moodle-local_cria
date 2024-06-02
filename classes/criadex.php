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



namespace local_cria;

use local_cria\gpt;

class criadex
{

    /**
     * @param $data
     * @param string $type azure,cohere
     * @return void
     */
    public static function create_model($data, $type = 'azure')
    {
        // Get Config
        $config = get_config('local_cria');
        // Create model
        return gpt::_make_call(
            $config->criadex_url,
            $config->criadex_api_key,
            $data,
            '/models/' . $type . '/create',
            'POST'
        );
    }

    /**
     * @param $model_id
     * @param $data
     * @param string $type azure,cohere
     * @return void
     */
    public static function update_model($model_id, $data, $type = 'azure')
    {
        // Get config
        $config = get_config('local_cria');
        // Update model
        return gpt::_make_call(
            $config->criadex_url,
            $config->criadex_api_key,
            $data,
            '/models/' . $type . '/' . $model_id . '/update',
            'PATCH'
        );
    }

    /**
     * @param $model_id
     * @return void
     */
    public static function delete_model($model_id, $type = 'azure')
    {
        // Get config
        $config = get_config('local_cria');
        // Update model
        return gpt::_make_call(
            $config->criadex_url,
            $config->criadex_api_key,
            [],
            '/models/' . $type . '/' . $model_id . '/delete',
            'DELETE'
        );
    }

    /**
     * @param $model_id
     * @return void
     */
    public static function about_model($model_id, $type = 'azure')
    {
        // Get config
        $config = get_config('local_cria');
        // Update model
        return gpt::_make_call(
            $config->criadex_url,
            $config->criadex_api_key,
            [], '/models/' . $type . '/' . $model_id . '/about',
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
        $top_p = 0.1,
        $provider = 'azure'
    )
    {
        // Get config
        $config = get_config('local_cria');

        // Build data object
        $data = [
            'max_reply_tokens' => $max_tokens,
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
            '/models/' . $provider . '/' . $model_id . '/agents/chat',
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