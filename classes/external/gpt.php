<?php

// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * External Web Service Template
 *
 * @package    localwstemplate
 * @copyright  2011 Moodle Pty Ltd (http://moodle.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
require_once($CFG->libdir . "/externallib.php");
require_once("$CFG->dirroot/config.php");

use local_cria\gpt;
use local_cria\criabot;
use local_cria\criadex;
use local_cria\logs;

class local_cria_external_gpt extends external_api {
    //**************************** SEARCH USERS **********************

    /*     * ***********************
     * Delete Record
     */

    /**
     * Returns description of method parameters
     * @return external_function_parameters
     */
    public static function response_parameters() {
        return new external_function_parameters(
            array(
                'bot_id' => new external_value(PARAM_INT, 'ID of the bot being used', false, 0),
                'chat_id' => new external_value(PARAM_RAW, 'Chat ID from indexing server', false, 0),
                'prompt' => new external_value(PARAM_TEXT, 'Question asked by user', false, ''),
                'content' => new external_value(PARAM_RAW, 'User content', false, '')
            )
        );
    }

    /**
     * @param $id
     * @return true
     * @throws dml_exception
     * @throws invalid_parameter_exception
     * @throws restricted_context_exception
     */
    public static function response($bot_id, $chat_id, $prompt, $content) {
        global $CFG, $USER, $DB, $PAGE;

        //Parameter validation
        $params = self::validate_parameters(self::response_parameters(), array(
                'bot_id' => $bot_id,
                'chat_id' => $chat_id,
                'prompt' => $prompt,
                'content' => $content
            )
        );
        //Context validation
        //OPTIONAL but in most web service it should present
        $context = \context_system::instance();
        self::validate_context($context);

        if ($chat_id != 0) {
            $result = criabot::chat_send($chat_id, $prompt);
            // Clean up content
            $content = nl2br(htmlspecialchars($result->reply->content->content));
            $content = gpt::make_email($content);
            $content = gpt::make_link($content);

            $message = new \stdClass();
            $message->message = $content;
            // Get token usage
            $token_usage = $result->reply->token_usage;
            // loop through token usage and add the prompt tokens and completion tokens
            $prompt_tokens = 0;
            $completion_tokens = 0;
            $total_tokens = 0;
            foreach ($token_usage as $token) {
                $prompt_tokens = $prompt_tokens + $token->prompt_tokens;
                $completion_tokens = $completion_tokens + $token->completion_tokens;
                $total_tokens = $total_tokens + $token->total_tokens;
            }
            $message->prompt_tokens = $prompt_tokens;
            $message->completion_tokens = $completion_tokens;
            $message->total_tokens = $total_tokens;
            $message->cost = gpt::_get_cost($bot_id, $prompt_tokens, $completion_tokens);
            // Insert logs
            logs::insert(
                $bot_id,
                $prompt,
                $content,
                $prompt_tokens,
                $completion_tokens,
                $total_tokens,
                $message->cost,
                $result->reply->context);
        } else {
            $message = gpt::get_response($bot_id, $prompt, $content, false);
        }

        return json_encode($message);
    }

    /**
     * Returns description of method result value
     * @return external_description
     */
    public static function response_returns() {
        return new external_value(PARAM_RAW, 'Message returned by gpt');
    }

}
