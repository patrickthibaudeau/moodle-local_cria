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
        global $DB;
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
            ct.use_bot_server
        From
            {local_cria_bot} b Inner Join
            {local_cria_type} ct On ct.id = b.bot_type
        Where
            b.publish = 1 
        Order By
            b.name";

        $result = $DB->get_records_sql($sql);
        $result = array_values($result);
	    return $result;
    }

}