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
 * Create Date: 27-07-2023
 * License: LGPL 
 * 
 */
namespace local_cria;

class bots {

    // Bot type factual
    const BOT_TYPE_FACTUAL = 1;

    // Bot type transcription
    const BOT_TYPE_TRANSCRIPTION = 2;
	/**
	 *
	 *@var string
	 */
	private $results;

    private $user_id;

	/**
	 *
	 *@global \moodle_database $DB
	 */
	public function __construct($user_id = null) {
	    global $DB, $USER;

        if ($user_id == null) {
            $user_id = $USER->id;
        }
        $sql = "
        Select
            b.id,
            b.name,
            b.description,
            b.bot_type,
            b.system_reserved,
            b.publish,
            ct.name As type_name,
            ct.use_bot_server,
            b.bot_system_message,
            b.theme_color,
            ct.use_bot_server,
            b.usermodified,
            b.timecreated,
            b.timemodified,
            date_format(from_unixtime(b.timecreated), '%d/%m/%Y') as timecreated_hr,
            date_format(from_unixtime(b.timemodified), '%d/%m/%Y') as timemodified_hr
        From
            {local_cria_bot} b Inner Join
            {local_cria_type} ct On ct.id = b.bot_type";
        // if not site admin, only show bots user has access too
        if (!is_siteadmin($user_id)) {
            $sql .= " Inner Join
            {local_cria_bot_role} cbr On cbr.bot_id = b.id Inner Join
            {local_cria_capability_assign} cca On cca.bot_role_id = cbr.id
        Where
            cca.user_id = $user_id";
        }

      $sql .=  "\n Order By
            b.name";
	    $this->results = $DB->get_records_sql($sql);
	}

	/**
	  * Get records
	 */
	public function get_records(): array {
        global $CFG;
        include_once($CFG->dirroot . '/local/cria/lib.php');
        // For each bot, get user capabilities
        $BOT_CAPABILITIES = new bot_capabilities();
        $system_capabilites = $BOT_CAPABILITIES->get_cria_system_capabilities();
        foreach ($this->results as $bot) {
           foreach($system_capabilites as $sc) {
               $bot->{$sc->name} = has_bot_capability($sc->name, $bot->id);
           }
        }
	    return $this->results;
	}

	/**
	  * Array to be used for selects
	  * Defaults used key = record id, value = name 
	  * Modify as required. 
	 */
	public function get_select_array(): array {
	    $array = [
	        '' => get_string('select', 'local_cria')
	      ];
	      foreach($this->results as $r) {
	            $array[$r->id] = $r->name;
	      }
	    return $array;
	}

    static public function get_bot_types() {
        global $DB;
        $types = $DB->get_records('local_cria_type', []);
        $bot_types = [];
        $bot_types[0] = get_string('select', 'local_cria');
        foreach ($types as $type) {
            $bot_types[$type->id] = $type->name;
        }
        return $bot_types;
    }

    public function get_published_bots() {
        global $CFG, $DB;
        $path = $CFG->wwwroot . '/local/cria/bot_apps/?id=';
        $plugin_path = $CFG->wwwroot . '/local/cria/plugins';
        $sql = "
        Select
            b.id,
            b.name,
            b.description,
            b.plugin_path,
            b.bot_type,
            b.system_reserved,
            b.publish,
            ct.name As type_name,
            ct.use_bot_server,
            b.bot_system_message,
            ct.use_bot_server
        From
            {local_cria_bot} b Inner Join
            {local_cria_type} ct On ct.id = b.bot_type
        Where
            b.publish = 1 
        Order By
            b.name";

        $result = $DB->get_records_sql($sql);
        foreach ($result as $r) {
            if($r->plugin_path == '') {
                $r->plugin_path = $path . $r->id;
            } else {
                $r->plugin_path = $plugin_path . $r->plugin_path;
            }

        }
        // Based on the number of results, create a new array with no more than 3 elements per chunk. Each new chunk
        // is identified as items and elemnets are added to it.
        $result = array_chunk($result, 3, true);

        $results = [];
        for ($i = 0; $i < count($result); $i++) {
            $results[$i]['items'] = array_values($result[$i]);
        }

        $results = array_values($results);
	    return $results;
    }

    /**
     * @return array
     * @throws \dml_exception
     */
    public function get_available_child_bots($bot_id) {
        global $DB;
        // Get all bots that are available as child bots
        $bots = $DB->get_records('local_cria_bot', ['available_child' => 1], 'name');
        $available_bots = [];
        foreach ($bots as $bot) {
            if ($bot->id != $bot_id) {
                $available_bots[$bot->id] = $bot->name;
            }
        }

        return $available_bots;
    }

}