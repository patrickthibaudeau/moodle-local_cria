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

use local_cria\intent;
use local_cria\criabot;
use local_cria\questions;

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
        // Delete question from moodle
        $DB->delete_records('local_cria_question', ['id' => $id]);
        // Delete example questions
        $DB->delete_records('local_cria_question_example', ['questionid' => $id]);
        if ($delete_on_bot_server->status == 200) {
            return 200;
        } else {
            return 404;
        }
    }

    /**
     * Returns description of method result value
     * @return external_description
     */
    public static function delete_returns() {
        return new external_value(PARAM_INT, 'return code');
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
        //Get example
        $question_example = $DB->get_record('local_cria_question_example', ['id' => $id]);

        $params['value'] = strip_tags($params['value']);
        $params['timemodified'] = time();
        $params['indexed'] = 0;
        $params['usermodified'] = $USER->id;
        // Update record
        $DB->update_record('local_cria_question_example', $params);
        // Set publised to 0 for question
        $question_params = [
            'id' => $question_example->questionid,
            'published' => 0,
            'timemodified' => time(),
            'usermodified' => $USER->id
        ];
        $DB->update_record('local_cria_question', $question_params);
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

    //*********************Delete exmaple question*************************
    /**
     * Returns description of method parameters
     * @return external_function_parameters
     */
    public static function delete_example_parameters() {
        return new external_function_parameters(
            array(
                'id' => new external_value(PARAM_INT, 'Question id', true)
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
    public static function delete_example($id) {
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
        //Get example
        $question_example = $DB->get_record('local_cria_question_example', ['id' => $id]);

        $DB->delete_records('local_cria_question_example', ['id' => $id]);
        // Set publised to 0 for question
        $DB->set_field('local_cria_question', 'published', 0, ['id' => $question_example->questionid]);
        return true;
    }

    /**
     * Returns description of method result value
     * @return external_description
     */
    public static function delete_example_returns() {
        return new external_value(PARAM_BOOL, 'return code');
    }

    //*********************Create example question*************************
    /**
     * Returns description of method parameters
     * @return external_function_parameters
     */
    public static function insert_example_parameters()
    {
        return new external_function_parameters(
            array(
                'questionid' => new external_value(PARAM_INT, 'Question id', true),
                'value' => new external_value(PARAM_TEXT, 'Example question value', true)
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
    public static function insert_example($question_id, $value)
    {
        global $CFG, $USER, $DB, $PAGE;

        //Parameter validation
        $params = self::validate_parameters(self::insert_example_parameters(), array(
                'questionid' => $question_id,
                'value' => $value
            )
        );

        //Context validation
        //OPTIONAL but in most web service it should present
        $context = \context_system::instance();
        self::validate_context($context);


        $params['timecreated'] = time();
        $params['timemodified'] = time();
        $params['indexed'] = 0;
        $params['usermodified'] = $USER->id;
        // Update record
        $new_id = $DB->insert_record('local_cria_question_example', $params);
        // Set publised to 0 for question
        $DB->set_field('local_cria_question', 'published', 0, ['id' => $question_id]);

        return $new_id;
    }

    /**
     * Returns description of method result value
     * @return external_description
     */
    public static function insert_example_returns()
    {
        return new external_value(PARAM_INT, 'Return new record id');
    }

    /*************************Delete all questions*************************/
    /**
     * Returns description of method parameters
     * @return external_function_parameters
     */
    public static function delete_all_parameters() {
        return new external_function_parameters(
            array(
                'intent_id' => new external_value(PARAM_INT, 'Intent id', false, 0)
            )
        );
    }

    /**
     * @param $intent_id Int
     * @return true
     * @throws dml_exception
     * @throws invalid_parameter_exception
     * @throws restricted_context_exception
     */
    public static function delete_all($intent_id) {
        global $CFG, $USER, $DB, $PAGE;

        //Parameter validation
        $params = self::validate_parameters(self::delete_all_parameters(), array(
                'intent_id' => $intent_id
            )
        );

        //Context validation
        //OPTIONAL but in most web service it should present
        $context = \context_system::instance();
        self::validate_context($context);

        $QUESTIONS = new questions($intent_id);
        $status = $QUESTIONS->delete_all();

        return 200;
    }

    /**
     * Returns description of method result value
     * @return external_description
     */
    public static function delete_all_returns() {
        return new external_value(PARAM_INT, 'return code');
    }
}
