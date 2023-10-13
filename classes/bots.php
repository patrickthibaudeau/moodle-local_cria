<?php
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

	/**
	 *
	 *@global \moodle_database $DB
	 */
	public function __construct() {
	    global $DB, $USER;
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
            {local_cria_type} ct On ct.id = b.bot_type
        Order By
            b.name";
	    $this->results = $DB->get_records_sql($sql);
	}

	/**
	  * Get records
	 */
	public function get_records() {
	    return $this->results;
	}

	/**
	  * Array to be used for selects
	  * Defaults used key = record id, value = name 
	  * Modify as required. 
	 */
	public function get_select_array() {
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

}