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

use local_cria\bot;
use local_cria\file;
use local_cria\files;
use local_cria\gpt;

class cria
{
    public static function create_model() {
        $data = [
            "name" => "test",
            "description" => "test",
            "model_type" => "llm",
            "azure_credentials" => [
                "api_base" => "https://api.crai.ai",
                "api_version" => "v1",
                "api_key" => "crai-api-key",
                "api_deployment" => "crai-deployment",
                "api_model" => "crai-model"
            ],
            "azure_credentials" => [
                "api_base" => "https://api.crai.ai",
                "api_version" => "v1",
                "api_key" => "crai-api-key",
                "api_deployment" => "crai-deployment",
                "api_model" => "crai-model"
            ]
        ];

        $data = json_encode($data);
        // Create bot
        return gpt::_make_call($bot_id, $data, 'models', 'POST', true);
    }

    /**
     * Create a bot on the indexing server
     * @param $bot_id
     * @return mixed|null
     * @throws \coding_exception
     * @throws \dml_exception
     */
    public static function create_bot($bot_id)
    {
        $BOT = new bot($bot_id);
        $system_message = $BOT->get_bot_type_system_message() . ' ' . $BOT->get_bot_system_message();

        $llm = $BOT->get_model_config();
        $embedding = $BOT->get_embedding_config();

        $data = [
            "system_message" => $system_message,
            "chat_expires_seconds" => 900,
            "embed_enabled" => true,
            "azure_credentials" => [
                "llm" => [
                    "api_base" => $llm->azure_endpoint,
                    "api_version" => $llm->azure_api_version,
                    "api_key" => $llm->azure_key,
                    "api_deployment" => $llm->azure_deployment_name,
                    "api_model" => $llm->model_name
                ],
                "embedding" => [
                    "api_base" => $embedding->azure_endpoint,
                    "api_version" => $embedding->azure_api_version,
                    "api_key" => $embedding->azure_key,
                    "api_deployment" => $embedding->azure_deployment_name,
                    "api_model" => $embedding->model_name
                ]
            ],
            "embed_enabled" => true,
            "embed_bot_name" => $BOT->get_name(),
            "embed_bot_sub_name" => $BOT->get_description(),
            "embed_bot_greeting" => $BOT->get_welcome_message(),
            "embed_bot_icon_url" => null
        ];

        $data = json_encode($data);
        // Create bot
        $new_bot = gpt::_make_call($bot_id, $data, 'create', 'POST', true);

        if (isset($new_bot->status)) {
            if ($new_bot->status == 409) {
                return \core\notification::error(get_string('bot_already_exists', 'local_cria'));
            }
        }

        return $new_bot;
    }

    /**
     * Get bot configuration
     * @param $bot_id
     * @return mixed
     * @throws \dml_exception
     */
    public static function get_bot($bot_id)
    {

        // Create bot
        return gpt::_make_call($bot_id, [], 'config', 'GET', true);
    }

    /**
     * @param $bot_id
     * @return mixed
     * @throws \dml_exception
     */
    public static function delete_bot($bot_id)
    {
        $BOT = new bot($bot_id);

        return gpt::_make_call($bot_id, [], 'delete', 'DELETE', true);
    }

    /**
     * @param $bot_id
     * @return mixed
     * @throws \dml_exception
     */
    public static function update_bot($bot_id)
    {
        $BOT = new bot($bot_id);
        $system_message = $BOT->get_bot_type_system_message() . ' ' . $BOT->get_bot_system_message();

        $llm = $BOT->get_model_config();
        $embedding = $BOT->get_embedding_config();

        $data = [
            "system_message" => $system_message,
            "chat_expires_seconds" => 900,

            "azure_credentials" => [
                "llm" => [
                    "api_base" => $llm->azure_endpoint,
                    "api_version" => $llm->azure_api_version,
                    "api_key" => $llm->azure_key,
                    "api_deployment" => $llm->azure_deployment_name,
                    "api_model" => $llm->model_name
                ],
                "embedding" => [
                    "api_base" => $embedding->azure_endpoint,
                    "api_version" => $embedding->azure_api_version,
                    "api_key" => $embedding->azure_key,
                    "api_deployment" => $embedding->azure_deployment_name,
                    "api_model" => $embedding->model_name
                ]
            ],
            "embed_enabled" => true,
            "embed_bot_name" => $BOT->get_name(),
            "embed_bot_sub_name" => $BOT->get_description(),
            "embed_bot_greeting" => $BOT->get_welcome_message(),
            "embed_bot_icon_url" => null
        ];

        $data = json_encode($data);
        // Create bot
        return gpt::_make_call($bot_id, $data, 'config_bulk', 'PATCH', true);
    }

    /**
     * @param $bot_id
     * @return mixed
     * @throws \dml_exception
     */
    public static function get_files($bot_id)
    {
        // Create bot
        return gpt::_make_call($bot_id, '', 'files', 'GET', true);
    }

    /**
     * @param $bot_id
     * @return mixed
     * @throws \dml_exception
     */
    public static function add_file($bot_id, $file_path, $file_name)
    {
        global $CFG;

        // Get config to use later for the indexing server api key
        $config = get_config('local_cria');
        // Create objects
        $BOT = new bot($bot_id);


        // Initiate CURL
        $curl = curl_init();
        // Set parameters
        curl_setopt_array($curl, array(
            CURLOPT_URL => $config->bot_server_url . 'bots/' . $bot_id
                . '/files/?x-api-key=' . $config->bot_server_api_key,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_HTTPHEADER => array(
                'accept: application/json',
                'Content-Type: multipart/form-data'
            ),
            CURLOPT_POSTFIELDS => array(
                'files' => new \CURLFILE(
                    $file_path,
                    'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                    $file_name
                ),
            ),
        ));
        // Upload file
        $result = curl_exec($curl);
        curl_close($curl);

        if ($result === false) {
            \core\notification::error('File upload failed.');
        } else {
            return json_decode($result);
        }
    }

    /**
     * @param $bot_id
     * @param $file File can be either the file name or the indexing server file id
     * @return mixed
     * @throws \dml_exception
     */
    public static function delete_file($bot_id, $file, $use_file_id = false)
    {
        // Create array
        $data = [
            $file
        ];
        $data = json_encode($data);
        // Make call
        return gpt::_make_call($bot_id, $data, 'files', 'DELETE', true);
    }

    /**
     * Start a chat session
     * @param $bot_id
     * @return mixed
     * @throws \dml_exception
     */
    public static function start_chat($bot_id)
    {
        // Make call
        return gpt::_make_call($bot_id, '', 'chats/start', 'POST', true);
    }

    /**
     * Send message for reply from GPT
     * @param $bot_id
     * @param $chat_id
     * @param $message
     * @return mixed
     * @throws \dml_exception
     */
    public static function send_chat_request($bot_id, $chat_id, $message)
    {
        // Create array
        $data = [
            "message" => $message
        ];
        $data = json_encode($data);
        // Make call
        return gpt::_make_call($bot_id, $data, 'chats/' . $chat_id . '/send', 'POST', true);
    }

    /**
     * Get chat summary
     * @param $bot_id
     * @return mixed
     * @throws \dml_exception
     */
    public static function get_chat_summary($bot_id)
    {
        // Make call
        return gpt::_make_call($bot_id, '', 'chats/summary', 'GET', true);
    }

    /**
     * Get caht history
     * @param $bot_id
     * @param $chat_id
     * @return mixed
     * @throws \dml_exception
     */
    public static function get_chat_history($bot_id, $chat_id)
    {
        // Make call
        $results = gpt::_make_call($bot_id, '', 'chats/' . $chat_id . '/history', 'GET', true);
        if ($results->status == 404) {
            \core\notification::error(get_string('chat_does_not_exist', 'cria'));
        }
    }

    /**
     * End chat session
     * @param $bot_id
     * @param $chat_id
     * @return mixed
     * @throws \dml_exception
     */
    public static function end_chat($bot_id, $chat_id)
    {
        // Make call
        return gpt::_make_call($bot_id, '', 'chats/' . $chat_id . '/end', 'DELETE', true);
    }

}