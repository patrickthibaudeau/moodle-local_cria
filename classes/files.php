<?php
/*
 * Author: Admin User
 * Create Date: 27-07-2023
 * License: LGPL 
 * 
 */
namespace local_cria;
use local_cria\bot;

class files {

	/**
	 *
	 *@var string
	 */
	private $results;

    /**
     * @var string
     */
    private $bot_id;

	/**
	 *
	 *@global \moodle_database $DB
	 */
	public function __construct($bot_id) {
	    global $DB;
        $this->bot_id = $bot_id;
	    $this->results = $DB->get_records('local_cria_files', array('bot_id' => $bot_id));
	}

	/**
	  * Get records
	 */
	public function get_records() {
	    return $this->results;
	}

    public function concatenate_content() {
        $content = '';
        foreach($this->results as $r) {
            $content .= $r->content;
        }
        return $content;
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

    public function get_bot_name() {
        $BOT = new bot($this->bot_id);
        return $BOT->get_name();
    }

}