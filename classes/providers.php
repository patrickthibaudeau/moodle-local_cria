<?php
/*
 * Author: Admin User
 * Create Date: 26-08-2023
 * License: LGPL 
 * 
 */
namespace local_cria;

class providers {

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
	    $this->results = $DB->get_records('local_cria_providers', [], 'name ASC');
	}

	/**
	  * Get records
	 */
	public function get_records() {
	    return $this->results;
	}

    /**
     * Get records
     */
    public function get_formated_records() {
        $results = $this->results;
        $providers = [];
        $i = 0;
        foreach($results as $r) {
            $PROVIDER = new provider($r->id);
            $providers[$i]['id'] = $PROVIDER->get_id();
            $providers[$i]['name'] = $PROVIDER->get_name();
            $providers[$i]['idnumber'] = $PROVIDER->get_idnumber();
            $providers[$i]['image'] = $PROVIDER->get_image();
            $providers[$i]['usermodified'] = $PROVIDER->get_usermodified();
            $providers[$i]['userfullname'] = $PROVIDER->get_usermodified_fullname();
            $providers[$i]['timecreated'] = $PROVIDER->get_timecreated();
            $providers[$i]['timemodified'] = $PROVIDER->get_timemodified();
            unset($PROVIDER);
            $i++;

        }

        return $providers;
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

        $results = $this->results;

	      foreach($results as $r) {
              $PROVIDER = new provider($r->id);
	            $array[$r->id] = $r->name . '<img src="' . $PROVIDER->get_image_url() . '" width="50px" height="auto" style="margin-left: 10px;">';
                unset($PROVIDER);
	      }
	    return $array;
	}

}