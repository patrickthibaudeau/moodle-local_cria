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

use local_cria\cria;
use local_cria\bot;
use local_cria\criabot;

/**
 * External Web Service Template
 *
 * @package    localwstemplate
 * @copyright  2011 Moodle Pty Ltd (http://moodle.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
require_once($CFG->libdir . "/externallib.php");
require_once("$CFG->dirroot/config.php");

class local_cria_external_criabot extends external_api
{

    /****** Start chat session ******/
    /**
     *
     * /**
     * Returns description of method parameters
     * @return external_function_parameters
     */
    public static function chat_start_parameters()
    {
        return new external_function_parameters(
            array()
        );
    }

    /**
     * @param $id
     * @return true
     * @throws dml_exception
     * @throws invalid_parameter_exception
     * @throws restricted_context_exception
     */
    public static function chat_start()
    {
        global $CFG, $USER, $DB, $PAGE;

        //Parameter validation
        $params = self::validate_parameters(self::chat_start_parameters(), array()
        );

        //Context validation
        //OPTIONAL but in most web service it should present
        $context = \context_system::instance();
        self::validate_context($context);

        $session = criabot::chat_start();

        return $session->chat_id;
    }

    /**
     * Returns description of method result value
     * @return external_description
     */
    public static function chat_start_returns()
    {
        return new external_value(PARAM_RAW, 'chat id');
    }

    /****** Check to see if BOT exists ******/
    /**
     *
     * /**
     * Returns description of method parameters
     * @return external_function_parameters
     */
    public static function bot_exists_parameters()
    {
        return new external_function_parameters(
            array(
                'bot_id' => new external_value(PARAM_INT, 'ID of the bot being used', false, 0),
                'bot_api_key' => new external_value(PARAM_RAW, 'BOT api key', false, ''),
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
    public static function bot_exists($bot_id, $bot_api_key)
    {
        global $CFG, $USER, $DB, $PAGE;

        //Parameter validation
        $params = self::validate_parameters(self::bot_exists_parameters(), array(
                'bot_id' => $bot_id,
                'bot_api_key' => $bot_api_key
            )
        );

        //Context validation
        //OPTIONAL but in most web service it should present
        $context = \context_system::instance();
        self::validate_context($context);

        // Check if bot exists
        if ($bot = $DB->get_record('local_cria_bot', array('id' => $bot_id))) {
            if ($intent = $DB->get_record('local_cria_intents', $params)) {
                return 200;
            } else {
                return 401;
            }
        } else {
            return 404;
        }

    }

    /**
     * Returns description of method result value
     * @return external_description
     */
    public static function bot_exists_returns()
    {
        return new external_value(PARAM_INT, 'boolean');
    }
}
