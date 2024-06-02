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

use mod_forum\local\managers\capability;

class bot_role extends crud
{


    /**
     *
     * @var int
     */
    private $id;

    /**
     *
     * @var int
     */
    private $bot_id;

    /**
     *
     * @var string
     */
    private $name;

    /**
     *
     * @var string
     */
    private $shortname;

    /**
     *
     * @var string
     */
    private $description;

    /**
     *
     * @var int
     */
    private $dortorder;

    /**
     *
     * @var int
     */
    private $usermodified;

    /**
     *
     * @var int
     */
    private $timecreated;

    /**
     *
     * @var string
     */
    private $timecreated_hr;

    /**
     *
     * @var int
     */
    private $timemodified;

    /**
     *
     * @var string
     */
    private $timemodified_hr;

    /**
     *
     * @var string
     */
    private $table;


    /**
     *
     *
     */
    public function __construct($id = 0)
    {
        global $CFG, $DB, $DB;

        $this->table = 'local_cria_bot_role';

        if ($id) {
            $this->id = $id;
            $result = $this->get_record();
        } else {
            $result = new \stdClass();
            $this->id = 0;
        }

        $this->bot_id = $result->bot_id ?? 0;
        $this->name = $result->name ?? '';
        $this->shortname = $result->shortname ?? '';
        $this->description = $result->description ?? '';
        $this->sortorder = $result->sortorder ?? 0;
        $this->usermodified = $result->usermodified ?? 0;
        $this->timecreated = $result->timecreated ?? 0;
        $this->timecreated_hr = '';
        if ($this->timecreated) {
            $this->timecreated_hr = strftime(get_string('strftimedate'), $result->timecreated);
        }
        $this->timemodified = $result->timemodified ?? 0;
        $this->timemodified_hr = '';
        if ($this->timemodified) {
            $this->timemodified_hr = strftime(get_string('strftimedate'), $result->timemodified);
        }
    }

    /**
     * @return \stdClass
     * @throws \dml_exception
     */
    public function get_record(): \stdClass
    {
        global $DB;
        $params = [
            'id' => $this->id
        ];
        return $DB->get_record($this->table, $params);
    }

    /**
     * @param $data
     * @return int
     * @throws \dml_exception
     */
    public function insert_record($data): int
    {
        global $DB, $USER;

        // Get sortorder
        $existing_roles = $DB->get_records($this->table, ['bot_id' => $data->botid], 'sortorder DESC');
        $existing_roles = array_values($existing_roles);

        $params = new \stdClass();
        $params->bot_id = $data->botid;
        $params->name = $data->name;
        $params->description = $data->description;
        $params->shortname = $data->shortname;
        $params->system_reserved = 0;
        $params->sortorder = $existing_roles[0]->sortorder + 1;
        // Set timecreated and timemodified
        if (!isset($data->timecreated)) {
            $params->timecreated = time();
        }
        if (!isset($data->imemodified)) {
            $params->timemodified = time();
        }
        //Set user
        $params->usermodified = $USER->id;

        $id = $DB->insert_record($this->table, $params);

        $data->bot_role_id = $id;
        // Create bot capabilites
        $BOT_CAPABILITIES = new bot_capabilities();
        $BOT_CAPABILITIES->create_bot_capabilities($data);

        return $id;
    }

    /**
     * @param $data
     * @return int
     * @throws \dml_exception
     */
    public function update_record($data): int
    {
        global $DB, $USER;
        // Set timemodified


        $params = new \stdClass();
        $params->id = $data->id;
        $params->name = $data->name;
        $params->description = $data->description;
        $params->shortname = $data->shortname;
        if (!isset($data->imemodified)) {
            $params->timemodified = time();
        }
        //Set user
        $params->usermodified = $USER->id;

        $DB->update_record($this->table, $params);
        // Create bot capabilites
        $BOT_CAPABILITIES = new bot_capabilities();
        $BOT_CAPABILITIES->update_bot_capabilities($data);

        return $data->id;
    }

    /**
     * @return bool
     * @throws \dml_exception
     */
    public function delete_record(): bool
    {
        global $DB;
        // Delete bor role record
        $params = [
            'id' => $this->id
        ];
        $DB->delete_records($this->table, $params);
        // Delete all capabilites for this bot role
        $bot_capabilites_params = [
            'bot_role_id' => $this->id
        ];
        $DB->delete_records('local_cria_bot_capabilities', $bot_capabilites_params);
        return true;
    }

    /**
     * @return id - bigint (18)
     */
    public function get_id(): int
    {
        return $this->id;
    }

    /**
     * @return bot_id - bigint (18)
     */
    public function get_bot_id(): int
    {
        return $this->bot_id;
    }

    /**
     * @return name - varchar (255)
     */
    public function get_name(): string
    {
        return $this->name;
    }

    /**
     * @return shortname - varchar (255)
     */
    public function get_shortname(): string
    {
        return $this->shortname;
    }

    /**
     * @return description - longtext (-1)
     */
    public function get_description(): string
    {
        return $this->description;
    }

    /**
     * @return usermodified - bigint (18)
     */
    public function get_sortorder(): int
    {
        return $this->sortorder;
    }

    /**
     * @return usermodified - bigint (18)
     */
    public function get_usermodified(): int
    {
        return $this->usermodified;
    }

    /**
     * @return timecreated - bigint (18)
     */
    public function get_timecreated(): int
    {
        return $this->timecreated;
    }

    /**
     * @return timemodified - bigint (18)
     */
    public function get_timemodified(): int
    {
        return $this->timemodified;
    }

    /**
     * @param Type: bigint (18)
     */
    public function set_id($id): void
    {
        $this->id = $id;
    }

    /**
     * @param Type: bigint (18)
     */
    public function set_bot_id($id): void
    {
        $this->id = $id;
    }

    /**
     * @param Type: varchar (255)
     */
    public function set_name($name): void
    {
        $this->name = $name;
    }

    /**
     * @param Type: varchar (255)
     */
    public function set_shortname($shortname): void
    {
        $this->shortname = $shortname;
    }

    /**
     * @param Type: longtext (-1)
     */
    public function set_description($description): void
    {
        $this->description = $description;
    }

    /**
     * @param Type: bigint (18)
     */
    public function set_sortorder($sortorder): void
    {
        $this->sortorder = $sortorder;
    }

    /**
     * @param Type: bigint (18)
     */
    public function set_usermodified($usermodified): void
    {
        $this->usermodified = $usermodified;
    }

    /**
     * @param Type: bigint (18)
     */
    public function set_timecreated($timecreated): void
    {
        $this->timecreated = $timecreated;
    }

    /**
     * @param Type: bigint (18)
     */
    public function set_timemodified($timemodified): void
    {
        $this->timemodified = $timemodified;
    }

    /**
     * Method creates default bot admin role for a bot
     * @param $bot_id
     * @return void
     */
    public function create_default_roles($bot_id)
    {
        global $CFG, $DB, $USER;
        // Get all capabilities for the system
        $BOT_CAPABILITIES = new bot_capabilities();
        $cria_capabilities = $BOT_CAPABILITIES->get_cria_system_capabilities();

        // Create Administrator bot_role record
        $role_data = new \stdClass();
        $role_data->bot_id = $bot_id;
        $role_data->name = 'Bot Administrator';
        $role_data->shortname = 'bot_admin';
        $role_data->description = 'This role has all permissions for this bot';
        $role_data->system_reserved = 1;
        $role_data->sortorder = 1;
        $role_data->usermodified = $USER->id;
        $role_data->timecreated = time();
        $role_data->timemodified = time();
        $role_id = $DB->insert_record($this->table, $role_data);

        $data = new \stdClass();
        $data->bot_role_id = $role_id;
        foreach ($cria_capabilities as $cc) {
            $data->name = $cc->name;
            $data->permission = 1;
            $data->usermodified = $USER->id;
            $data->timecreated = time();
            $data->timemodified = time();
            $DB->insert_record('local_cria_bot_capabilities', $data);
        }

        // Create Editor bot_role record
        $editor_role_data = new \stdClass();
        $editor_role_data->bot_id = $bot_id;
        $editor_role_data->name = 'Bot Editor';
        $editor_role_data->shortname = 'bot_editor';
        $editor_role_data->description = 'The editor role can edit the bot config and content but cannot delete the bot nor change permissions';
        $editor_role_data->system_reserved = 1;
        $editor_role_data->sortorder = 2;
        $editor_role_data->usermodified = $USER->id;
        $editor_role_data->timecreated = time();
        $editor_role_data->timemodified = time();
        $editor_role_data = $DB->insert_record($this->table, $editor_role_data);

        $editor_data = new \stdClass();
        $editor_data->bot_role_id = $editor_role_data;
        foreach ($cria_capabilities as $cc) {
            $editor_data->name = $cc->name;
            if ($cc->name == 'local/cria:bot_permissions' || $cc->name == 'local/cria:delete_bots') {
                $editor_data->permission = 0;
            } else {
                $editor_data->permission = 1;
            }
            $editor_data->usermodified = $USER->id;
            $editor_data->timecreated = time();
            $editor_data->timemodified = time();
            $DB->insert_record('local_cria_bot_capabilities', $editor_data);
        }
    }

    /**
     * Method checks to see if bot_admin exists for a bot
     * @param $bot_id
     * @return void
     */
    public function bot_admin_role_exists($bot_id)
    {
        global $CFG, $DB, $USER;

        $params = [
            'bot_id' => $bot_id,
            'shortname' => 'bot_admin',
            'system_reserved' => 1
        ];
        return $DB->get_record($this->table, $params);
    }

}