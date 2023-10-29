<?php
/*
 * Author: Admin User
 * Create Date: 21-10-2023
 * License: LGPL 
 * 
 */
namespace local_cria;

class capability_assign extends crud {


	/**
	 *
	 *@var int
	 */
	private $id;

	/**
	 *
	 *@var int
	 */
	private $bot_role_id;

	/**
	 *
	 *@var int
	 */
	private $user_id;

	/**
	 *
	 *@var int
	 */
	private $groupid;

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

		$this->table = 'local_cria_capability_assign';

      if ($id) {
         $this->id = $id;
         $result = $this->getRecord();
      } else {
        $result = new \stdClass();
         $this->id = 0;
      }

		$this->bot_role_id = $result->bot_role_id ?? 0;
		$this->user_id = $result->user_id ?? 0;
		$this->groupid = $result->groupid ?? 0;
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
	 * @return bot_role_id - bigint (18)
	 */
	public function getBot_role_id(){
		return $this->bot_role_id;
	}

	/**
	 * @return user_id - bigint (18)
	 */
	public function get_user_id(){
		return $this->user_id;
	}

	/**
	 * @return groupid - bigint (18)
	 */
	public function get_groupid(){
		return $this->groupid;
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
	public function get_yimecreated(){
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
	 * @param Type: bigint (18)
	 */
	public function set_bot_role_id($bot_role_id){
		$this->bot_role_id = $bot_role_id;
	}

	/**
	 * @param Type: bigint (18)
	 */
	public function set_user_id($user_id){
		$this->user_id = $user_id;
	}

	/**
	 * @param Type: bigint (18)
	 */
	public function set_groupid($groupid){
		$this->groupid = $groupid;
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