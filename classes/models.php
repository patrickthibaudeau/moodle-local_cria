<?php
/*
 * Author: Admin User
 * Create Date: 26-08-2023
 * License: LGPL 
 * 
 */
namespace local_cria;

class models {

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
	    global $DB;
	    $this->results = $DB->get_records('local_cria_models', [], 'name ASC');
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
	public function get_select_array($embedding = false) {
        global $DB;
	    $array = [
	        '' => get_string('select', 'local_cria')
	      ];

        if ($embedding) {
           $results =  $DB->get_records('local_cria_models', ['is_embedding' => 1], 'name ASC');
        } else {
            $results = $this->results;
        }
	      foreach($results as $r) {
	            $array[$r->id] = $r->name;
	      }
	    return $array;
	}

}