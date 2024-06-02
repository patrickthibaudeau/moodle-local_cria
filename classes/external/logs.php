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
use local_cria\logs;

class local_cria_external_logs extends external_api
{
    //**************************** SEARCH USERS **********************

    /*     * ***********************
     * Delete Record
     */

    /**
     * Returns description of method parameters
     * @return external_function_parameters
     */
    public static function insert_log_parameters()
    {
        return new external_function_parameters(
            array(
                'bot_id' => new external_value(PARAM_INT, 'ID of the bot being used', false, 0),
                'prompt' => new external_value(PARAM_TEXT, 'Question asked by user', false, ''),
                'message' => new external_value(PARAM_TEXT, 'Response from GPT', false, ''),
                'prompt_tokens' => new external_value(PARAM_INT, 'Number of prompt tokens used', false, 0),
                'completion_tokens' => new external_value(PARAM_INT, 'Number of completion tokens used', false, 0),
                'total_tokens' => new external_value(PARAM_INT, 'Total tokens', false, 0),
                'ip' => new external_value(PARAM_TEXT, 'Total tokens', false, ''),
                'user_id' => new external_value(PARAM_INT, 'User ID from within Cria server', false, 0),
                'index_context' => new external_value(PARAM_RAW, 'Context from index server', false, '')
            )
        );
    }

    /**
     * @param $bot_id
     * @param $prompt
     * @param $message
     * @param $prompt_tokens
     * @param $completion_tokens
     * @param $total_tokens
     * @param $ip
     * @param $user_id
     * @param $index_context
     * @param $confidence
     * @return false|string
     * @throws dml_exception
     * @throws invalid_parameter_exception
     * @throws restricted_context_exception
     */
    public static function insert_log(
        $bot_id,
        $prompt,
        $message,
        $prompt_tokens,
        $completion_tokens,
        $total_tokens,
        $ip,
        $user_id = 0,
        $index_context = ''
    )
    {
        global $CFG, $USER, $DB, $PAGE;

        //Parameter validation
        $params = self::validate_parameters(self::insert_log_parameters(), array(
                'bot_id' => $bot_id,
                'prompt' => $prompt,
                'message' => $message,
                'prompt_tokens' => $prompt_tokens,
                'completion_tokens' => $completion_tokens,
                'total_tokens' => $total_tokens,
                'ip' => $ip,
                'user_id' => $user_id,
                'index_context' => $index_context
            )
        );
        //Context validation
        $context = \context_system::instance();
        self::validate_context($context);

        $LOGS = new logs();
        $cost = gpt::_get_cost($bot_id, $prompt_tokens, $completion_tokens);
        // Insert log record
        $id = $LOGS->insert(
            $bot_id,
            $prompt,
            $message,
            $prompt_tokens,
            $completion_tokens,
            $total_tokens,
            $cost,
            $index_context,
            $ip,
            $user_id
        );

        return $id;
    }

    /**
     * Returns id of new log entry
     * @return external_description
     */
    public static function insert_log_returns()
    {
        return new external_value(PARAM_INT, 'New record id');
    }

}
