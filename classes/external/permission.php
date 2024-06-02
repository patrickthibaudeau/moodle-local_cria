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

class local_cria_external_permission extends external_api
{
    //**************************** SEARCH USERS **********************

    /*     * ***********************
     * Remove user from permision
     */

    /**
     * Returns description of method parameters
     * @return external_function_parameters
     */
    public static function unassign_user_role_parameters()
    {
        return new external_function_parameters(
            array(
                'id' => new external_value(PARAM_INT, 'assigned id', false, 0)
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
    public static function unassign_user_role($id)
    {
        global $CFG, $USER, $DB, $PAGE;

        //Parameter validation
        $params = self::validate_parameters(self::unassign_user_role_parameters(), array(
                'id' => $id
            )
        );

        //Context validation
        //OPTIONAL but in most web service it should present
        $context = \context_system::instance();
        self::validate_context($context);

        $DB->delete_records('local_cria_capability_assign', ['id' => $id]);

        return true;
    }

    /**
     * Returns description of method result value
     * @return external_description
     */
    public static function unassign_user_role_returns()
    {
        return new external_value(PARAM_INT, 'Boolean');
    }

    /**
     * Assign user
     */

    /**
     * Assign user to role
     * @return external_function_parameters
     */
    public static function assign_user_role_parameters()
    {
        return new external_function_parameters(
            array(
                'role_id' => new external_value(PARAM_INT, 'Role id', false, 0),
                'user_id' => new external_value(PARAM_INT, 'User id', false, 0)
            )
        );
    }

    /**
     * @param $role_id
     * @param $user_id
     * @return bool|int
     * @throws dml_exception
     * @throws invalid_parameter_exception
     * @throws restricted_context_exception
     */
    public static function assign_user_role($role_id, $user_id)
    {
        global $CFG, $USER, $DB, $PAGE;

        //Parameter validation
        $params = self::validate_parameters(self::assign_user_role_parameters(), array(
                'role_id' => $role_id,
                'user_id' => $user_id
            )
        );

        //Context validation
        //OPTIONAL but in most web service it should present
        $context = \context_system::instance();
        self::validate_context($context);

        $id = $DB->insert_record('local_cria_capability_assign', [
            'bot_role_id' => $role_id,
            'user_id' => $user_id,
            'usermodified' => $USER->id,
            'timecreated' => time(),
            'timemodified' => time()
        ]);

        return $id;
    }

    /**
     * Returns new record id
     * @return external_value
     */
    public static function assign_user_role_returns()
    {
        return new external_value(PARAM_INT, 'id of new record');
    }

    //**************************** GET USERS **********************
    /**
     * Returns users parameters
     * @return external_function_parameters
     */
    public static function get_users_parameters() {
        return new external_function_parameters(
            array(
                'role_id' => new external_value(PARAM_INT, 'Role id', true),
                'id' => new external_value(PARAM_INT, 'User id', false, -1),
                'name' => new external_value(PARAM_TEXT, 'User first or last name', false, null)
            )
        );
    }
    /**
     * Returns users
     * @global moodle_database $DB
     * @return string users
     */
    public static function get_users($role_id, $id = -1, $name = "") {
        global $DB;

        $params = self::validate_parameters(self::get_users_parameters(), array(
                'role_id' => $role_id,
                'id' => $id,
                'name' => $name
            )
        );

        if (strlen($name) >= 3) {
            $sql = "select * from {user} u where ";
            $name = str_replace(' ', '%', $name);
            $sql .= " (Concat(u.firstname, ' ', u.lastname ) like '%$name%' or "
                . "(u.idnumber like '%$name%') or "
                . "(u.email like '%$name%') or "
                . "(u.username like '%$name%'))"; //How the ajax call with search via the form autocomplete
            $sql .= " Order by u.lastname";
            //How the ajax call with search via the form autocomplete
            $mdl_users = $DB->get_records_sql($sql, array($name));
        }
        else {
//            $sql = "select * from {user} Order By lastname";
            $mdl_users = $DB->get_records('user', [], 'lastname');
        }

        // Get all assigned users for this role
        $assigned_users = $DB->get_records('local_cria_capability_assign', ['bot_role_id' => $role_id]);
        $assigned_users_array = [];
        foreach ($assigned_users as $au) {
            $assigned_users_array[] = $au->user_id;
        }

        $users = [];
        $i = 0;
        foreach ($mdl_users as $u) {
            // Do not add admin or guest users
            if (!in_array($u->id, $assigned_users_array)) {
                if ($u->id != 1 AND $u->id != 2) {
                    $users[$i]['id'] = $u->id;
                    $users[$i]['firstname'] = $u->firstname;
                    $users[$i]['lastname'] = $u->lastname;
                    $users[$i]['email'] = $u->email;
                    $users[$i]['idnumber'] = $u->idnumber;
                    $i++;
                }
            }
        }
        return $users;
    }

    /**
     * Get Users
     * @return single_structure_description
     */
    public static function user_details() {
        $fields = array(
            'id' => new external_value(PARAM_INT, 'Record id', false),
            'firstname' => new external_value(PARAM_TEXT, 'User first name', true),
            'lastname' => new external_value(PARAM_TEXT, 'User last name', true),
            'email' => new external_value(PARAM_TEXT, 'email', true),
            'idnumber' => new external_value(PARAM_TEXT, 'ID Number', true)
        );
        return new external_single_structure($fields);
    }

    /**
     * Returns users result value
     * @return external_description
     */
    public static function get_users_returns() {
        return new external_multiple_structure(self::user_details());
    }

    //**************************** GET ASSIGNED USERS **********************
    /**
     * Returns users parameters
     * @return external_function_parameters
     */
    public static function get_assigned_users_parameters() {
        return new external_function_parameters(
            array(
                'id' => new external_value(PARAM_INT, 'Role id', false, -1)
            )
        );
    }
    /**
     * Returns users
     * @global moodle_database $DB
     * @return string users
     */
    public static function get_assigned_users($id) {
        global $DB;

        $params = self::validate_parameters(self::get_assigned_users_parameters(), array(
                'id' => $id,
            )
        );

            $sql = "SELECT 
                        cca.id as id,
                        cca.user_id as user_id,
                        u.firstname as firstname,
                        u.lastname as lastname,
                        u.email as email,
                        u.idnumber as idnumber
                    FROM 
                        {local_cria_capability_assign} cca Inner Join
                        {user} u on cca.user_id = u.id
                    WHERE 
                        cca.bot_role_id = ?
                    ORDER BY u.lastname";

            $assigned_users = $DB->get_records_sql($sql, [$id]);


        $users = [];
        $i = 0;
        foreach ($assigned_users as $u) {
            $users[$i]['id'] = $u->id;
            $users[$i]['firstname'] = $u->firstname;
            $users[$i]['lastname'] = $u->lastname;
            $users[$i]['email'] = $u->email;
            $users[$i]['idnumber'] = $u->idnumber;
            $users[$i]['userid'] = $u->user_id;
            $i++;
        }
        return $users;
    }

    /**
     * Get Users
     * @return single_structure_description
     */
    public static function get_assigned_users_details() {
        $fields = array(
            'id' => new external_value(PARAM_INT, 'Record id', false),
            'firstname' => new external_value(PARAM_TEXT, 'User first name', true),
            'lastname' => new external_value(PARAM_TEXT, 'User last name', true),
            'email' => new external_value(PARAM_TEXT, 'email', true),
            'idnumber' => new external_value(PARAM_TEXT, 'ID Number', true),
            'userid' => new external_value(PARAM_TEXT, 'USER ID', true),
        );
        return new external_single_structure($fields);
    }

    /**
     * Returns users result value
     * @return external_description
     */
    public static function get_assigned_users_returns() {
        return new external_multiple_structure(self::get_assigned_users_details());
    }
}
