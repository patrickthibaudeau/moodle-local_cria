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

class criaparse
{
    public static function get_strategies() {
        // Get config
        $config = get_config('local_cria');
        $endpoint = $config->criaparse_url . '/parser/strategies?x-api-key=' . $config->criadex_api_key;

        // Create a cURL handle
        $ch = curl_init();

        // Set cURL options
        curl_setopt($ch, CURLOPT_URL, $endpoint);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Execute the cURL request
        $response = curl_exec($ch);

        // Check for errors
        if (curl_errno($ch)) {
            echo 'Error: ' . curl_error($ch);
        } else {
            // Process the response (e.g., decode JSON if applicable)
            $parsed_response = json_decode($response, true);
            return $parsed_response;
        }

        // Close the cURL handle
        curl_close($ch);
    }

    /******** Document Content ********/
    /**
     * Parse document content
     * @param $llm_model_id String
     * @param $embedding_model_id String
     * @param $strategy String
     * @param $file_path String
     * @param $mime_type String
     * @return Array
     */
    public static function execute($llm_model_id, $embedding_model_id, $strategy, $file_path, $mime_type = 'application/vnd.openxmlformats-officedocument.wordprocessingml.document') {
        // Get config
        $config = get_config('local_cria');
        $endpoint = $config->criaparse_url . '/parser/parse?strategy=' . $strategy
            . '&llm_model_id=' . $llm_model_id . '&embedding_model_id=' . $embedding_model_id
            .  '&x-api-key=' . $config->criadex_api_key;

        // Create a cURL handle
        $ch = curl_init();

        // Set cURL options
        curl_setopt($ch, CURLOPT_URL, $endpoint);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, [
            'file' => new \CURLFile($file_path, $mime_type)
        ]);

        // Execute the cURL request
        $response = curl_exec($ch);

        // Check for errors
        if (curl_errno($ch)) {
            echo 'Error: ' . curl_error($ch);
        } else {
            // Process the response (e.g., decode JSON if applicable)
            $parsed_response = json_decode($response, true);
            return $parsed_response;
        }

        // Close the cURL handle
        curl_close($ch);
    }

    /**
     * Set parsing strategy based on file type. If docx, use strategy from bot. Otherwise, use generic strategy.
     * @param $file_type String
     * @param $current_strategy String
     * @return String
     */
    public static function set_parsing_strategy_based_on_file_type($file_type, $current_strategy) {
        $strategy = '';
        switch ($file_type) {
            case 'docx':
                $strategy = $current_strategy;
                break;
            default:
                $strategy = 'GENERIC';
                break;
        }
        return $strategy;
    }
}