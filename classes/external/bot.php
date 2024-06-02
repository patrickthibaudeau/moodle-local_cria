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

use local_cria\bot;
use local_cria\cria;

/**
 * External Web Service Template
 *
 * @package    localwstemplate
 * @copyright  2011 Moodle Pty Ltd (http://moodle.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
require_once($CFG->libdir . "/externallib.php");
require_once("$CFG->dirroot/config.php");

class local_cria_external_bot extends external_api
{
    //**************************** SEARCH USERS **********************

    /*     * ***********************
     * Delete Record
     */

    /**
     * Returns description of method parameters
     * @return external_function_parameters
     */
    public static function delete_parameters()
    {
        return new external_function_parameters(
            array(
                'id' => new external_value(PARAM_INT, 'Content id', false, 0)
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
    public static function delete($id)
    {
        global $CFG, $USER, $DB, $PAGE;

        //Parameter validation
        $params = self::validate_parameters(self::delete_parameters(), array(
                'id' => $id
            )
        );

        //Context validation
        //OPTIONAL but in most web service it should present
        $context = \context_system::instance();
        self::validate_context($context);
        $BOT = new bot($id);

        // Delete bot on indexing server
        $BOT = new bot($id);
        $BOT->delete_record();

        return true;
    }

    /**
     * Returns description of method result value
     * @return external_description
     */
    public static function delete_returns()
    {
        return new external_value(PARAM_INT, 'Boolean');
    }

    /******** Create new bot ************/

    /**
     * Returns description of method parameters
     * @return external_function_parameters
     */
    public static function create_bot_parameters()
    {
        return new external_function_parameters(
            array(
                'name' => new external_value(PARAM_TEXT, 'Bot name', false, ''),
                'description' => new external_value(PARAM_TEXT, 'Bot description', false, ''),
                'bot_type' => new external_value(PARAM_INT, 'Bot type', false, 0),
                'model_id' => new external_value(PARAM_INT, 'Cria GPT model used', false, 0),
                'embedding_id' => new external_value(PARAM_INT, 'Bot server', false, 0),
                'bot_system_message' => new external_value(PARAM_TEXT, 'Bot system message', false, ''),
                'requires_content_prompt' => new external_value(PARAM_INT, 'Requires content prompt', false, 0),
                'requires_user_prompt' => new external_value(PARAM_INT, 'Requires user prompt', false, 0),
                'user_prompt' => new external_value(PARAM_TEXT, 'User prompt', false, ''),
                'publish' => new external_value(PARAM_INT, 'Publish', false, 0),
                'welcome_message' => new external_value(PARAM_TEXT, 'Welcome message for embedded bot', false, ''),
                'theme_color' => new external_value(PARAM_TEXT, 'Hex code for embedded bot color. Default Red', false, '#e31837'),
                'max_tokens' => new external_value(PARAM_INT, 'Max tokens', false, 1024),
                'temperature' => new external_value(PARAM_FLOAT, 'Temperature', false, 0.1),
                'top_p' => new external_value(PARAM_FLOAT, 'Top p', false, 0.1),
                'top_k' => new external_value(PARAM_INT, 'Top k', false, 1),
                'min_relevance' => new external_value(PARAM_FLOAT, 'Min relevance', false, 0.9),
                'max_context' => new external_value(PARAM_INT, 'Max context', false, 0),
                'no_context_message' => new external_value(PARAM_TEXT, 'No context message', false, 'Nothing found')
            )
        );
    }

    /**
     * @param $name
     * @param $description
     * @param $bot_type
     * @param $model_id
     * @param $embedding_id
     * @param $bot_system_message
     * @param $requires_content_prompt
     * @param $requires_user_prompt
     * @param $publish
     * @param $welcome_message
     * @param $theme_color
     * @return true
     * @throws dml_exception
     * @throws invalid_parameter_exception
     * @throws restricted_context_exception
     */
    public static function create_bot(
        $name,
        $description,
        $bot_type,
        $model_id = 0,
        $embedding_id = 0,
        $bot_system_message = '',
        $requires_content_prompt = 0,
        $requires_user_prompt = 1,
        $user_prompt = '',
        $publish = 0,
        $welcome_message = '',
        $theme_color = '#e31837',
        $max_tokens = 1024,
        $temperature = 0.1,
        $top_p = 0.1,
        $top_k = 1,
        $min_relevance = 0.9,
        $max_context = 0,
        $no_context_message = 'Nothing found',
    )
    {
        global $CFG, $USER, $DB, $PAGE;

        //Parameter validation
        $params = self::validate_parameters(self::create_bot_parameters(), array(
                'name' => $name,
                'description' => $description,
                'bot_type' => $bot_type,
                'model_id' => $model_id,
                'embedding_id' => $embedding_id,
                'bot_system_message' => $bot_system_message,
                'requires_content_prompt' => $requires_content_prompt,
                'requires_user_prompt' => $requires_user_prompt,
                'user_prompt' => $user_prompt,
                'publish' => $publish,
                'welcome_message' => $welcome_message,
                'theme_color' => $theme_color,
                'max_tokens' => $max_tokens,
                'temperature' => $temperature,
                'top_p' => $top_p,
                'top_k' => $top_k,
                'min_relevance' => $min_relevance,
                'max_context' => $max_context,
                'no_context_message' => $no_context_message
            )
        );

        //Context validation
        //OPTIONAL but in most web service it should present
        $context = \context_system::instance();
        self::validate_context($context);
        // Create bot
        $BOT = new bot();
        $id = $BOT->insert_record((object)$params);
        $NEW_BOT = new bot($id);
        if ($NEW_BOT->use_bot_server()) {
            $NEW_BOT->create_bot_on_bot_server();
        }

        unset($BOT);
        unset($NEW_BOT);

        return $id;
    }

    /**
     * Returns new bot id
     * @return external_description
     */
    public static function create_bot_returns()
    {
        return new external_value(PARAM_INT, 'New bot id');
    }





    /***** Get Prompt *****/

    /**
     * Returns description of method parameters for get_prompt methof
     * @return external_function_parameters
     */
    public static function get_prompt_parameters()
    {
        return new external_function_parameters(
            array(
                'bot_id' => new external_value(PARAM_INT, 'Bot id', false, 0)
            )
        );
    }

    /**
     * @param $bot_id
     * @return string The prompt if there is one available
     * @throws dml_exception
     * @throws invalid_parameter_exception
     * @throws restricted_context_exception
     */
    public static function get_prompt($bot_id)
    {
        global $CFG, $USER, $DB, $PAGE;

        //Parameter validation
        $params = self::validate_parameters(self::get_prompt_parameters(), array(
                'bot_id' => $bot_id
            )
        );

        //Context validation
        //OPTIONAL but in most web service it should present
        $context = \context_system::instance();
        self::validate_context($context);
        $BOT = new bot($bot_id);

        return $BOT->get_user_prompt();
    }

    /**
     * Returns prompt if there is one available
     * @return external_description
     */
    public static function get_prompt_returns()
    {
        return new external_value(PARAM_RAW, 'Prompt if there is one available');
    }
}
