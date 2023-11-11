<?php
/*
 * Author: Admin User
 * Create Date: 21-10-2023
 * License: LGPL 
 * 
 */
namespace local_cria;

class bot_roles {

	/**
	 *
	 *@var string
	 */
	private $results;

	/**
	 *
	 *@global \moodle_database $DB
	 */
	public function __construct(int $bot_id) {
	    global $DB;
	    $this->results = $DB->get_records('local_cria_bot_role', ['bot_id' => $bot_id], 'sortorder ASC');
	}

	/**
	  * Get records
	 */
	public function get_records() {
	    return $this->results;
	}

    public function get_records_for_template() {
        return array_values($this->results);
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

}