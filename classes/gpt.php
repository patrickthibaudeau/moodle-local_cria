<?php

namespace local_cria;

use local_cria\bot;
use local_cria\logs;
use local_cria\criadex;

class gpt
{

    /**
     * @param $service_url
     * @param $api_key
     * @param $data
     * @param $call
     * @param $method
     * @param $file_path
     * @param $file_name
     * @return mixed
     * @throws \dml_exception
     */
    public static function _make_call(
        $service_url,
        $api_key,
        $data,
        $call = '',
        $method = 'GET',
        $file_path = false,
        $file_name = false
    )
    {
        $config = get_config('local_cria');

        $ch = curl_init();
        // Set curl attributes for regular API calls
        // If there is a file path, then it's a file upload
        if ($file_path == false) {
            curl_setopt_array($ch, array(
                    CURLOPT_CUSTOMREQUEST => $method,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_POSTFIELDS => $data,
                    CURLOPT_TIMEOUT => 2000,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                )
            );
            // Set headers
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    'Content-Type: application/json',
                    'x-api-key: ' . $api_key
                )
            );
        } else {
            // Params for file upload
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POST, 1);

// Set headers
            $headers = [
                'Accept: application/json',
                'x-api-key: ' . $api_key,
                'Content-Type: multipart/form-data',
            ];
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

// Set POST data (multipart/form-data)
            $post_data = [
                'file' => new \CURLFile(
                    $file_path,
                    'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                    $file_name
                ),
            ];
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        }

        // Set URL
        $url = $service_url . $call;
        curl_setopt($ch, CURLOPT_URL, $url);

        $result = json_decode(curl_exec($ch));
        curl_close($ch);

        return $result;
    }


    /**
     * Build the message to send to the API
     * @param int $bot_id
     * @param string $prompt
     * @return object
     * @throws \dml_exception
     */
    protected static function _build_message($bot_id, $prompt, $content = '')
    {
        // Create object that will return the data
        $data = new \stdClass();
        $BOT = new bot($bot_id);
        $system_message = $BOT->get_bot_system_message();
        // Get crai paramaeters
        $params = json_decode($BOT->get_bot_parameters_json());

        $user_content = $content;
        // Remove all lines and replace with space. AKA lower token count
//        $user_content = preg_replace('/\s+/', ' ', trim($user_content));
//        if there is user content, split into chunks
        if ($user_content) {
            // Get number of words in content and split it into chunks if it's too long
            $chunk_text = self::_split_into_chunks($user_content);
            // Determine the context window size (overlap)
            $context_window_size = 50;
            $prompt_tokens = 0;
            $completion_tokens = 0;
            $total_tokens = 0;
            $summary = [];
            $i = 0;
            // Loop through the chunks and send them to the API
            foreach ($chunk_text as $i => $chunk) {
                // Add the previous response's tail as the context for the current chunk
                if ($i > 0) {
                    $context = substr($chunk_text[$i - 1], -$context_window_size);
                    $chunk = $context . $chunk;
                }
                $full_prompt = $chunk . "\n" . $prompt;
                // Use Criadex to make the call
                $result = criadex::query(
                    $params->llm_model_id,
                    $params->system_message,
                    $full_prompt,
                    $params->max_tokens,
                    $params->temperature,
                    $params->top_p
                    );
                // Add the number of tokens used for the prompt to the total tokens
                $prompt_tokens = $prompt_tokens + $result->response->raw->usage->prompt_tokens;
                $completion_tokens = $completion_tokens + $result->response->raw->usage->completion_tokens;
                $total_tokens = $total_tokens + $result->response->raw->usage->total_tokens;
                // Capture the response
                $summary[] = $result->response->message->content;
            }
            if (count($summary) > 1) {
                $content_prompt = '';
                $sentences = '';
                foreach ($summary as $i => $response) {
                    if ($response != '') {
                        $sentences .= $response . "\n";
                    }
                }
                $content_prompt .= $sentences;
                $content_prompt .= "Question: Please answer with a boolean only to the following question. In the sentences provided above, do all the sentences mean the same thing?\n";
                // Compare results
                $comparison_result = criadex::query(
                    $params->llm_model_id,
                    'You compare text. You only answer with a single boolean. You return the boolean that appears more often.',
                    $content_prompt,
                    $params->max_tokens,
                    $params->temperature,
                    $params->top_p
                );
                // Add the number of tokens used for the comparison to the total tokens
                $prompt_tokens = $prompt_tokens + $comparison_result->response->raw->usage->prompt_tokens;
                $completion_tokens = $completion_tokens + $comparison_result->response->raw->usage->completion_tokens;
                $total_tokens = $total_tokens + $comparison_result->response->raw->usage->total_tokens;

                $answer = $comparison_result->choices[0]->message->content;
                if ($answer == 'True') {
                    $summaries = $summary[0];
                } else {
                    $summaries = implode('', $summary);
                }
            } else {
                // Implode the chunks into one string
                $summaries = implode('', $summary);
            }
        } else {
            $messages = [
                'messages' => [
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
            $result = criadex::query(
                $params->llm_model_id,
                $params->system_message,
                $prompt,
                $params->max_tokens,
                $params->temperature,
                $params->top_p
            );
            $summaries = $result->response->message->content;
            // Add the number of tokens used for the prompt to the total tokens
            $prompt_tokens = $result->response->raw->usage->prompt_tokens;
            $completion_tokens = $result->response->raw->usage->completion_tokens;
            $total_tokens = $result->response->raw->usage->total_tokens;
        }

        // Get the cost of the call
        $cost = self::_get_cost($bot_id, $prompt_tokens, $completion_tokens);
        // Add to logs
        logs::insert($bot_id, $prompt, $summaries, $prompt_tokens, $completion_tokens, $total_tokens, $cost);

        $data->prompt_tokens = $prompt_tokens;
        $data->completion_tokens = $completion_tokens;
        $data->total_tokens = $total_tokens;
        $data->cost = $cost;
        $data->message = $summaries;
        return $data;
    }

    /**
     * Split a long text into smaller chunks
     * @param $long_text
     * @return array
     */
    protected static function _split_into_chunks($long_text)
    {
        // plugin config
        $config = get_config('local_cria');

        // Define the chunk size (maximum words per chunk)
        $chunk_size = $config->max_chunks;

        // Determine the context window size (overlap)
        $context_window_size = 50;

        // Initialize an array to store the chunked text
        $chunks = [];

        // Get the length of the long text
        $text_length = str_word_count($long_text);

        // Determine the context window size (overlap)
        $context_window_size = 50;

        // Split the long text into smaller chunks with overlap
        $chunks = array_chunk(str_split($long_text, $chunk_size - $context_window_size), 1);
        $chunks = array_map('implode', $chunks);

        return $chunks;
    }

    /**
     * Take a string and turn any valid URLs into HTML links
     * @param $input
     * @return array|string|string[]|null
     */
    public static function make_link($input)
    {
        $url_pattern = '<https?:\/\/(www\.)?[-a-zA-Z0-9@:%._\+~#=]{1,256}\.[a-zA-Z0-9()]{1,6}\b([-a-zA-Z0-9()@:%_\+.~#?&//=]*)>';
        $str = preg_replace($url_pattern, '<a href="$0" target="_blank">$0</a> ', $input);
        // Remove duplicate links
        return preg_replace('/\[.*\]/', '', $str);
    }

    /**
     * Take a string and turn any valid emails into HTML links
     * @param $input
     * @return array|string|string[]|null
     */
    public static function make_email($input)
    {
        //Detect and create email
        $mail_pattern = "/([A-z0-9\._-]+\@[A-z0-9_-]+\.)([A-z0-9\_\-\.]{1,}[A-z])/";
        return preg_replace($mail_pattern, '<a href="mailto:$1$2">$1$2</a>', $input);

    }

    /**
     * Get cost of API call
     * @param $prompt_tokens
     * @param $completion_tokens
     * @return float
     * @throws \dml_exception
     */
    public static function _get_cost($bot_id, $prompt_tokens, $completion_tokens): float
    {
        // plugin config
        $BOT = new bot($bot_id);
        $model = $BOT->get_model_config();

        $prompt_cost = ($prompt_tokens / 1000) * $model->prompt_cost;
        $completion_cost = ($completion_tokens / 1000) * $model->completion_cost;
        $cost = $prompt_cost + $completion_cost;
        return $cost;
    }

    /**
     * Get the response from the API
     * @param $bot_id
     * @param $prompt
     * @param $content
     * @return object
     * @throws \dml_exception
     */
    public static function get_response($bot_id, $prompt, $content = '', $use_bot_server = false): object
    {
        // Build the message
        $data = self::_build_message($bot_id, $prompt, $content);
        $message = $data->message;

        // Format the message
        if (isset($message)) {
            $message = nl2br(htmlspecialchars($message));
            $message = self::make_link($message);
            $message = self::make_email($message);
        }

        $data->message = $message;

        return $data;
    }

    /**
     * Used for automatic testing and comparing text
     * @param $bot_id
     * @param $prompt
     * @return string
     * @throws \dml_exception
     */
    public static function compare_text($response, $answer): string
    {
        $content_prompt = '';
        $sentences = "Text 1: " . $response . "\n\nText 2: " . $answer;
        $content_prompt .= $sentences;
        "Question: Please answer with a boolean only to the following question. In the two texts provided above, do the two texts mean the same thing?\n";
        $messages = [
            'messages' => [
                [
                    'role' => 'system',
                    'content' => 'You compare text. You only answer with a single boolean. You return the boolean that appears more often.'
                ],
                [
                    'role' => 'user',
                    'content' => $content_prompt
                ]
            ]
        ];

        $comparison_result = self::_make_call(json_encode($messages));
        return $comparison_result->choices[0]->message->content;
    }


}