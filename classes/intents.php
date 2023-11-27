<?php
/*
 * Author: Admin User
 * Create Date: 27-07-2023
 * License: LGPL 
 * 
 */
namespace local_cria;

class intents {


	/**
	 *
	 *@var array
	 */
	private $results;

    /**
     * @var
     */
    private $table;

    private $user_id;

	/**
	 *
	 *@global \moodle_database $DB
	 */
	public function __construct($bot_id) {
	    global $DB, $USER;

        $this->table = 'local_cria_intents';

        $sql = "
        Select
            *,
            date_format(from_unixtime(timecreated), '%d/%m/%Y') as timecreated_hr,
            date_format(from_unixtime(timemodified), '%d/%m/%Y') as timemodified_hr
        From
            {local_cria_intents}
        Where 
            bot_id = ?
        Order By
             name";
	    $this->results = $DB->get_records_sql($sql,[$bot_id]);
	}

	/**
	  * Get records
	 */
	public function get_records(): array {
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

}