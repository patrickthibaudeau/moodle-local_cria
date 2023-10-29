<?php
/*
 * Author: Admin User
 * Create Date: 21-10-2023
 * License: LGPL 
 * 
 */
namespace local_cria;

class bot_role extends crud {


	/**
	 *
	 *@var int
	 */
	private $id;

	/**
	 *
	 *@var string
	 */
	private $name;

	/**
	 *
	 *@var string
	 */
	private $shortname;

	/**
	 *
	 *@var string
	 */
	private $description;

	/**
	 *
	 *@var int
	 */
	private $usermodified;

	/**
	 *
	 *@var int
	 */
	private $timecreated;

	/**
	 *
	 *@var string
	 */
	private $timecreated_hr;

	/**
	 *
	 *@var int
	 */
	private $timemodified;

	/**
	 *
	 *@var string
	 */
	private $timemodified_hr;

	/**
	 *
	 *@var string
	 */
	private $table;


    /**
     *
     *
     */
	public function __construct($id = 0){
  	global $CFG, $DB, $DB;

		$this->table = 'local_cria_bot_role';

      if ($id) {
         $this->id = $id;
         $result = $this->getRecord();
      } else {
        $result = new \stdClass();
         $this->id = 0;
      }

		$this->name = $result->name ?? '';
		$this->shortname = $result->shortname ?? '';
		$this->description = $result->description ?? '';
		$this->usermodified = $result->usermodified ?? 0;
		$this->timecreated = $result->timecreated ?? 0;
          $this->timecreated_hr = '';
          if ($this->timecreated) {
		        $this->timecreated_hr = strftime(get_string('strftimedate'),$result->timecreated);
          }
		$this->timemodified = $result->timemodified ?? 0;
      $this->timemodified_hr = '';
          if ($this->timemodified) {
		        $this->timemodified_hr = strftime(get_string('strftimedate'),$result->timemodified);
          }
	}







	/**
	 * @return id - bigint (18)
	 */
	public function get_id(){
		return $this->id;
	}

	/**
	 * @return name - varchar (255)
	 */
	public function get_name(){
		return $this->name;
	}

	/**
	 * @return shortname - varchar (255)
	 */
	public function get_shortname(){
		return $this->shortname;
	}

	/**
	 * @return description - longtext (-1)
	 */
	public function get_description(){
		return $this->description;
	}

	/**
	 * @return usermodified - bigint (18)
	 */
	public function get_usermodified(){
		return $this->usermodified;
	}

	/**
	 * @return timecreated - bigint (18)
	 */
	public function get_timecreated(){
		return $this->timecreated;
	}

	/**
	 * @return timemodified - bigint (18)
	 */
	public function get_timemodified(){
		return $this->timemodified;
	}

	/**
	 * @param Type: bigint (18)
	 */
	public function set_id($id){
		$this->id = $id;
	}

	/**
	 * @param Type: varchar (255)
	 */
	public function set_name($name){
		$this->name = $name;
	}

	/**
	 * @param Type: varchar (255)
	 */
	public function set_shortname($shortname){
		$this->shortname = $shortname;
	}

	/**
	 * @param Type: longtext (-1)
	 */
	public function set_description($description){
		$this->description = $description;
	}

	/**
	 * @param Type: bigint (18)
	 */
	public function set_usermodified($usermodified){
		$this->usermodified = $usermodified;
	}

	/**
	 * @param Type: bigint (18)
	 */
	public function set_timecreated($timecreated){
		$this->timecreated = $timecreated;
	}

	/**
	 * @param Type: bigint (18)
	 */
	public function set_timemodified($timemodified){
		$this->timemodified = $timemodified;
	}

}