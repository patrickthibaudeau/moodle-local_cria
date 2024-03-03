<?php
/*
 * Author: Admin User
 * Create Date: 26-08-2023
 * License: LGPL 
 * 
 */
namespace local_cria;

use local_cria\provider;

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
     * @return array
     */
    public function get_formated_records() {
        $results = $this->results;
        $models = [];
        $i = 0;
        foreach($results as $r) {
            $MODEL = new model($r->id);
            $models[$i]['id'] = $MODEL->get_id();
            $models[$i]['name'] = $MODEL->get_name();
            $models[$i]['value'] = $MODEL->get_value();
            $models[$i]['provider_idnumber'] = $MODEL->get_provider_idnumber();
            $models[$i]['provider_name'] = $MODEL->get_provider_name();
            $models[$i]['usermodified'] = $MODEL->get_usermodified();
            $models[$i]['timecreated'] = $MODEL->get_timecreated();
            $models[$i]['timemodified'] = $MODEL->get_timemodified();
            unset($MODEL);
            $i++;

        }

        return $models;

    }

	/**
	  * Array to be used for selects
	  * Defaults used key = record id, value = name 
	  * Modify as required. 
	 */
	public function get_select_array($embedding = false, $rerank = false) {
        global $DB;
	    $array = [
	        '' => get_string('select', 'local_cria')
	      ];

        if ($embedding && !$rerank) {
           $results =  $DB->get_records('local_cria_models', ['is_embedding' => 1], 'name ASC');
        } else if (!$embedding && $rerank) {
            //  Get cohere provider id
            $provider = $DB->get_record('local_cria_providers', ['idnumber' => 'cohere']);
            $results = $DB->get_records('local_cria_models', ['provider_id' => $provider->id], 'name ASC');
        } else {
            $results = $DB->get_records('local_cria_models', ['is_embedding' => 0], 'name ASC');
        }
	      foreach($results as $r) {
	            $array[$r->id] = $r->name;
	      }
	    return $array;
	}

}