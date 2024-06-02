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


/*
 * Author: Admin User
 * Create Date: 21-10-2023
 * License: LGPL 
 * 
 */

namespace local_cria;

class bot_capabilities
{

    /**
     *
     * @global \moodle_database $DB
     */
    public function __construct()
    {

    }

    /**
     * Method creates a list of capabilites based on existing bot capabilities for the exising bot role
     * If no bot role exists, it lists all cria system capabilities
     * @return \stdClass
     * @throws \dml_exception
     */
    public function get_role_capabilities($bot_role_id = 0): \stdClass
    {
        global $DB;
        $from_system = false;
        // If there is a bot_role_id, get the capabilities for that bot role
        if ($bot_role_id > 0) {
            $role_capabilities = $DB->get_records(
                'local_cria_bot_capabilities',
                [
                    'bot_role_id' => $bot_role_id
                ]
            );
            // Identify if permission is true or false. If true, add checked to array
            foreach ($role_capabilities as $rc) {
                if ($rc->permission == 1) {
                    $rc->checked = 'checked';
                } else {
                    $rc->checked = '';
                }
            }
        } else {
            $role_capabilities = $this->get_cria_system_capabilities();
            $from_system = true;
        }

        foreach ($role_capabilities as $rc) {
            $string = str_replace('local/', '', $rc->name);
            $rc->stringname = get_string($string, 'local_cria');
        }
        // Return object with records and from_system flag
        $data = new \stdClass();
        $data->records = array_values($role_capabilities);
        $data->from_system = $from_system;
        return $data;
    }

    /**
     * returns all capabilities for the cria system
     * @return array
     * @throws \dml_exception
     */
    public function get_cria_system_capabilities(): array
    {
        global $DB;
        $cria_capabilities_sql = "SELECT 
                                    * 
                                  FROM 
                                      {capabilities} 
                                  WHERE 
                                      name LIKE 'local/cria%'
                                  ";
        $cria_capabilities = $DB->get_records_sql($cria_capabilities_sql);

        // Remove array
        $ignore_capabilites = [
            'local/cria:delete_bot_types',
            'local/cria:delete_models',
            'local/cria:edit_bot_types',
            'local/cria:edit_models',
            'local/cria:edit_system_reserved',
            'local/cria:groups',
            'local/cria:view_bots',
            'local/cria:view_bot_types',
            'local/cria:view_models',
            'local/cria:view_providers',
        ];

        foreach ($cria_capabilities as $key => $cc) {
            if (in_array($cc->name, $ignore_capabilites)) {
                unset($cria_capabilities[$key]);
            }
        }

        return $cria_capabilities;
    }

    /**
     * @param $data
     * @return void
     * @throws \dml_exception
     */
    public function create_bot_capabilities($data) : void
    {
        global $DB, $USER;
        // Since we are creating we will get all capabilities for the system
        $cria_capabilities = $this->get_cria_system_capabilities();
        foreach ($cria_capabilities as $cc) {
            $data->name = $cc->name;
            // If key exists in data, set permission to 1, else set to 0
            if (isset($data->{$cc->name})) {
                $data->permission = 1;
            } else {
                $data->permission = 0;
            }
            $data->usermodified = $USER->id;
            $data->timecreated = time();
            $data->timemodified = time();
            $DB->insert_record('local_cria_bot_capabilities', $data);
        }
    }

    /**
     * @param $data
     * @return void
     * @throws \dml_exception
     */
    public function update_bot_capabilities($data) : void
    {
        global $DB, $USER;
        // Since we are creating we will get all capabilities for the system
        $cria_capabilities = $this->get_role_capabilities($data->id);
        $records = $cria_capabilities->records;

        foreach ($cria_capabilities->records as $cc) {
            // If key exists in data, set permission to 1, else set to 0
            if (isset($data->{$cc->name})) {
                $cc->permission = 1;
            } else {
                $cc->permission = 0;
            }
            $cc->usermodified = $USER->id;
            $cc->timemodified = time();
            $DB->update_record('local_cria_bot_capabilities', $cc);
        }
    }
}