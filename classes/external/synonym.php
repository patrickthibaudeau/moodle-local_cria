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

use local_cria\keyword;
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

class local_cria_external_synonym extends external_api {
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
                'id' => new external_value(PARAM_INT, 'Synonym id', false, 0)
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

       if ($DB->delete_records('local_cria_synonyms', ['id' => $id])) {
           return true;
       } else {
           return false;
       }
    }

    /**
     * Returns description of method result value
     * @return external_description
     */
    public static function delete_returns() {
        return new external_value(PARAM_BOOL, 'return true/false');
    }

    //*********************update example question*************************
    /**
     * Returns description of method parameters
     * @return external_function_parameters
     */
    public static function update_parameters()
    {
        return new external_function_parameters(
            array(
                'id' => new external_value(PARAM_INT, 'Synonym id', true),
                'value' => new external_value(PARAM_RAW, 'Synonym value', true)
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
    public static function update($id, $value)
    {
        global $CFG, $USER, $DB, $PAGE;

        //Parameter validation
        $params = self::validate_parameters(self::update_parameters(), array(
                'id' => $id,
                'value' => $value
            )
        );

        //Context validation
        $context = \context_system::instance();
        self::validate_context($context);

        $params['value'] = strip_tags($params['value']);
        $params['timemodified'] = time();
        $params['usermodified'] = $USER->id;
        // Update record
        $DB->update_record('local_cria_synonyms', $params);

       return true;
    }

    /**
     * Returns description of method result value
     * @return external_description
     */
    public static function update_returns()
    {
        return new external_value(PARAM_BOOL, 'return ture or false');
    }


    //*********************Create example question*************************
    /**
     * Returns description of method parameters
     * @return external_function_parameters
     */
    public static function insert_parameters()
    {
        return new external_function_parameters(
            array(
                'keyword_id' => new external_value(PARAM_INT, 'Keyword id', true),
                'value' => new external_value(PARAM_TEXT, 'Synonym value', true)
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
    public static function insert($keyword_id, $value)
    {
        global $CFG, $USER, $DB, $PAGE;

        //Parameter validation
        $params = self::validate_parameters(self::insert_parameters(), array(
                'keyword_id' => $keyword_id,
                'value' => $value
            )
        );

        //Context validation
        //OPTIONAL but in most web service it should present
        $context = \context_system::instance();
        self::validate_context($context);


        $params['timecreated'] = time();
        $params['timemodified'] = time();
        $params['usermodified'] = $USER->id;
        // Update record
        $new_id = $DB->insert_record('local_cria_synonyms', $params);

        return $new_id;
    }

    /**
     * Returns description of method result value
     * @return external_description
     */
    public static function insert_returns()
    {
        return new external_value(PARAM_INT, 'Return new record id');
    }
}
