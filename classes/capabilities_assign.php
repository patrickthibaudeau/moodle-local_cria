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

class capabilities_assign
{

    /**
     *
     * @var string
     */
    private $results;

    /**
     *
     * @var int
     */
    private $user_id;

    /**
     *
     * @var int
     */
    private $bot_id;

    /**
     *
     * @global \moodle_database $DB
     */
    public function __construct($bot_id, $user_id)
    {
        global $DB;
        // Only get 1 role assignment, the highest priority
        $sql = "SELECT 
                    cca.id,
                    cca.bot_role_id,
                    cca.user_id,
                    cca.groupid,
                    cca.usermodified,
                    cca.timecreated,
                    cbr.name,
                    cbr.bot_id,
                    cbr.sortorder
                FROM 
                    {local_cria_capability_assign} cca Inner Join
                    {local_cria_bot_role} cbr ON cca.bot_role_id = cbr.id
                WHERE 
                        cca.user_id = ? AND
                        cbr.bot_id = ?
                ORDER BY
                    cbr.sortorder ASC 
                    LIMIT 1";

        $this->results = $DB->get_record_sql($sql, [$user_id, $bot_id]);

        $this->user_id = $user_id;
        $this->bot_id = $bot_id;
    }

    /**
     * Get records
     */
    public function get_records(): array
    {
        return [];
    }

    public function get_record() {
        return $this->results;
    }

    public function get_user_capabilities($bot_role_id = null)
    {
        global $DB;
        // if is site admin, get all capabilites
        $role_capabilities = '';
        $user_capabilities = [];
        $CAPABILITIES = new bot_capabilities();
        $system_capabilites = $CAPABILITIES->get_cria_system_capabilities();
        if (is_siteadmin($this->user_id)) {
            $role_capabilities = $CAPABILITIES->get_role_capabilities(0);
        } else {
            if ($bot_role_id) {
                $role_capabilities = $CAPABILITIES->get_role_capabilities($bot_role_id);
            }
        }

        if ($role_capabilities != '') {
            $records = $role_capabilities->records;
            foreach ($records as $r) {
                // If site admin, get all capabilities
                if (is_siteadmin($this->user_id)) {
                    $user_capabilities[] = $r->name;
                } else {
                    if ($r->permission == 1) {
                        $user_capabilities[] = $r->name;
                    }
                }
            }
        }

        return $user_capabilities;
    }

    /**
     * Array to be used for selects
     * Defaults used key = record id, value = name
     * Modify as required.
     */
    public function get_select_array(): array
    {
        $array = [
            '' => get_string('select', 'local_cria')
        ];
        foreach ($this->results as $r) {
            $array[$r->id] = $r->name;
        }
        return $array;
    }

}