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

use local_cria\file;
use local_cria\files;
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

class local_cria_external_content extends external_api {
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

        $FILE = new file($id);
        // Delete file from indexing server
        $result = criabot::document_delete($FILE->get_bot_name(), $FILE->get_name());
        // Delete file from database
        $DB->delete_records('local_cria_files', array('id' => $id));
        if ($result->status == 200) {
            return $result->status;
        } else {
            $error = 'Status: ' . $result->status .
                ' Code: ' . $result->code .
                ' Message: ' . $result->message;
            return  $error;
        }
    }

    /**
     * Returns description of method result value
     * @return external_description
     */
    public static function delete_returns() {
        return new external_value(PARAM_TEXT, 'return code');
    }

    //**************************** PUBLISH URLS **********************
    /**
     * Returns description of method parameters
     * @return external_function_parameters
     */
    public static function publish_urls_parameters() {
        return new external_function_parameters(
            array(
                'intent_id' => new external_value(PARAM_INT, 'Intent id', false, 0),
                'urls' => new external_value(PARAM_RAW, 'Web page URLs', false, '')
            )
        );
    }

    /**
     * @param $intent_id
     * @param $urls
     * @return string
     * @throws dml_exception
     * @throws invalid_parameter_exception
     * @throws restricted_context_exception
     */
    public static function publish_urls($intent_id, $urls) {
        global $CFG, $USER, $DB, $PAGE;

        //Parameter validation
        $params = self::validate_parameters(self::publish_urls_parameters(), array(
                'intent_id' => $intent_id,
                'urls' => $urls
            )
        );

        //Context validation
        //OPTIONAL but in most web service it should present
        $context = \context_system::instance();
        self::validate_context($context);

        // Convert urls to an array of urls seperated by new line
        $urls = explode("\n", $urls);
        $FILES = new files($intent_id);

        $result = $FILES->publish_urls($urls);
        if ($result->status == 200) {
            return $result->status;
        } else {
            $error = 'Status = ' . $result->status .
                '<br> Message =  ' . $result->message;
            return  $error;
        }

    }

    /**
     * Returns description of method result value
     * @return external_description
     */
    public static function publish_urls_returns() {
        return new external_value(PARAM_RAW, 'return code');
    }

    //**************************** Republish all files **********************
    /**
     * Returns description of method parameters
     * @return external_function_parameters
     */
    public static function publish_files_parameters() {
        return new external_function_parameters(
            array(
                'intent_id' => new external_value(PARAM_INT, 'Intent id', false, 0),
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
    public static function publish_files($intent_id) {
        global $CFG, $USER, $DB, $PAGE;

        //Parameter validation
        $params = self::validate_parameters(self::publish_files_parameters(), array(
                'intent_id' => $intent_id
            )
        );

        //Context validation
        //OPTIONAL but in most web service it should present
        $context = \context_system::instance();
        self::validate_context($context);

        $FILES = new files($intent_id);
        $FILES->publish_all_files();

        return true;
    }

    /**
     * Returns description of method result value
     * @return external_description
     */
    public static function publish_files_returns() {
        return new external_value(PARAM_BOOL, 'True');
    }


}
