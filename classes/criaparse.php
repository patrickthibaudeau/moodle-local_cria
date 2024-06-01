<?php
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
     * Upload a document associated to the bot
     * @param $bot_name String
     * @param $file_path String
     * @param $file_name String
     * @return void
     */
    public static function execute($strategy, $file_path, $mime_type = 'application/vnd.openxmlformats-officedocument.wordprocessingml.document') {
        // Get config
        $config = get_config('local_cria');
        $endpoint = $config->criaparse_url . '/parser/parse?strategy=' . $strategy . '&x-api-key=' . $config->criadex_api_key;

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