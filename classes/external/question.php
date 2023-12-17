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

use local_cria\intent;
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

class local_cria_external_question extends external_api {
    //**************************** SEARCH USERS **********************

    /*     * ***********************
     * Delete Record
     */

    /**
     * Returns description of method parameters
     * @return external_function_parameters
     */
    public static function delete_parameters() {
        return new external_function_parameters(
            array(
                'id' => new external_value(PARAM_INT, 'Question id', false, 0)
            )
        );
    }

    /**
     * @param $id Int
     * @return true
     * @throws dml_exception
     * @throws invalid_parameter_exception
     * @throws restricted_context_exception
     */
    public static function delete($id) {
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

       // Get the question based on id
        $question = $DB->get_record('local_cria_question', ['id' => $id]);
        $INTENT = new intent($question->intent_id);
        // Delete question from criabot
        $delete_on_bot_server = criabot::question_delete($INTENT->get_bot_name(), $question->document_name);
        if ($delete_on_bot_server->status == 200) {
            // Delete question from moodle
            $DB->delete_records('local_cria_question', ['id' => $id]);
            // Delete example questions
            $DB->delete_records('local_cria_question_example', ['questionid' => $id]);
            return 200;
        } else {
            \core\notification::error('Error deleting question on bot server. STATUS: '
                . $delete_on_bot_server->status . ' MESSAGE: ' . $delete_on_bot_server->message);
            return false;
        }
    }

    /**
     * Returns description of method result value
     * @return external_description
     */
    public static function delete_returns() {
        return new external_value(PARAM_BOOL, 'return code');
    }

    //*********************Get answer by question id*************************

    /**
     * Returns description of method parameters
     * @return external_function_parameters
     */
    public static function get_answer_parameters()
    {
        return new external_function_parameters(
            array(
                'id' => new external_value(PARAM_INT, 'Question id', false, 0)
            )
        );
    }

    /**
     * @param $id
     * @return array
     * @throws dml_exception
     * @throws invalid_parameter_exception
     * @throws restricted_context_exception
     */
    public static function get_answer($id)
    {
        global $CFG, $USER, $DB, $PAGE;

        //Parameter validation
        $params = self::validate_parameters(self::get_answer_parameters(), array(
                'id' => $id
            )
        );

        //Context validation
        //OPTIONAL but in most web service it should present
        $context = \context_system::instance();
        self::validate_context($context);

       // Get the question based on id
        $question = $DB->get_record('local_cria_question', ['id' => $id]);
        // Update record

        return (array)$question;
    }

    /**
     * Returns description of method result value
     * @return external_description
     */
    public static function get_answer_returns()
    {
        $fields = array(
            'answer' => new external_value(PARAM_RAW, 'Answer', true)
        );
        return new external_single_structure($fields);
    }

    //*********************Publish question*************************
    /**
     * Returns description of method parameters
     * @return external_function_parameters
     */
    public static function publish_parameters()
    {
        return new external_function_parameters(
            array(
                'id' => new external_value(PARAM_INT, 'Question id', false, 0)
            )
        );
    }

    /**
     * @param $id
     * @return array
     * @throws dml_exception
     * @throws invalid_parameter_exception
     * @throws restricted_context_exception
     */
    public static function publish($id)
    {
        global $CFG, $USER, $DB, $PAGE;

        //Parameter validation
        $params = self::validate_parameters(self::publish_parameters(), array(
                'id' => $id
            )
        );

        //Context validation
        //OPTIONAL but in most web service it should present
        $context = \context_system::instance();
        self::validate_context($context);

        // Get the question based on id
        $question = $DB->get_record('local_cria_question', ['id' => $id]);
        $INTENT = new intent($question->intent_id);
        $publish = $INTENT->publish_question($id);
        // Update record
        if ($publish) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Returns description of method result value
     * @return external_description
     */
    public static function publish_returns()
    {
        return new external_value(PARAM_BOOL, 'return ture or false');
    }

    //*********************update example question*************************
    /**
     * Returns description of method parameters
     * @return external_function_parameters
     */
    public static function update_example_parameters()
    {
        return new external_function_parameters(
            array(
                'id' => new external_value(PARAM_INT, 'Example question id', true),
                'value' => new external_value(PARAM_RAW, 'Example question value', true)
            )
        );
    }

    /**
     * @param $id
     * @param $value
     * @return bool
     * @throws dml_exception
     * @throws invalid_parameter_exception
     * @throws restricted_context_exception
     */
    public static function update_example($id, $value)
    {
        global $CFG, $USER, $DB, $PAGE;

        //Parameter validation
        $params = self::validate_parameters(self::update_example_parameters(), array(
                'id' => $id,
                'value' => $value
            )
        );

        //Context validation
        //OPTIONAL but in most web service it should present
        $context = \context_system::instance();
        self::validate_context($context);

        $params['value'] = strip_tags($params['value']);
        $params['timemodified'] = time();
        $params['indexed'] = 0;
        $params['userid'] = $USER->id;
        // Update record
        $DB->update_record('local_cria_question_example', $params);

       return true;
    }

    /**
     * Returns description of method result value
     * @return external_description
     */
    public static function update_example_returns()
    {
        return new external_value(PARAM_BOOL, 'return ture or false');
    }
}
