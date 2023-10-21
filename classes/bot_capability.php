<?php
/*
 * Author: Admin User
 * Create Date: 21-10-2023
 * License: LGPL 
 * 
 */
namespace local_cria;

class bot_capability extends crud {


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
	 *@var string
	 */
	private $capability;

	/**
	 *
	 *@var int
	 */
	private $permission;

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

		$this->table = 'local_cria_bot_capabilities';

      if ($id) {
         $this->id = $id;
         $result = $this->getRecord();
      } else {
        $result = new \stdClass();
         $this->id = 0;
      }

		$this->bot_role_id = $result->bot_role_id ?? 0;
		$this->capability = $result->capability ?? '';
		$this->permission = $result->permission ?? 0;
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
     * Get record
     *
     * @global \moodle_database $DB
     * 
     */
	public function getRecord(){
	    global $DB;
	    $result = $DB->get_record($this->table, ['id' => $this->id]);
	    return  $result;

	}

    /**
     * Delete the row 
     *
     * @global \moodle_database $DB
     *
     */
	public function deleteRecord(){
	    global $DB;
		$DB->delete_records($this->table,['id' => $this->id]);
	}

    /**
     * Insert record into selected table
     * @global \moodle_database $DB
     * @global \stdClass $USER
     * @param array or object $data
     */
	public function insertRecord($data){
		global $DB, $USER;

		if (is_object($data)) {
		    $data = convert_to_array($data);
		}

		if (!isset($data['timecreated'])) {
		    $data['timecreated'] = time();
		}

		if (!isset($data['timemodified'])) {
		    $data['timemodified'] = time();
		}

		//Set user
		$data['usermodified'] = $USER->id;

		$id = $DB->insert_record($this->table, $data);

		return $id;
	}

    /**
     * Update record into selected table
     * @global \moodle_database $DB
     * @global \stdClass $USER
     * @param array or object $data
     */
	public function updateRecord($data){
		global $DB, $USER;

		if (is_object($data)) {
		    $data = convert_to_array($data);
		}

		if (!isset($data['timemodified'])) {
		    $data['timemodified'] = time();
		}

		//Set user
		$data['usermodified'] = $USER->id;

		$id = $DB->update_record($this->table, $data);

		return $id;
	}

	/**
	 * @return id - bigint (18)
	 */
	public function getId(){
		return $this->id;
	}

	/**
	 * @return bot_role_id - bigint (18)
	 */
	public function getBot_role_id(){
		return $this->bot_role_id;
	}

	/**
	 * @return capability - varchar (255)
	 */
	public function getCapability(){
		return $this->capability;
	}

	/**
	 * @return permission - bigint (18)
	 */
	public function getPermission(){
		return $this->permission;
	}

	/**
	 * @return usermodified - bigint (18)
	 */
	public function getUsermodified(){
		return $this->usermodified;
	}

	/**
	 * @return timecreated - bigint (18)
	 */
	public function getTimecreated(){
		return $this->timecreated;
	}

	/**
	 * @return timemodified - bigint (18)
	 */
	public function getTimemodified(){
		return $this->timemodified;
	}

	/**
	 * @param Type: bigint (18)
	 */
	public function setId($id){
		$this->id = $id;
	}

	/**
	 * @param Type: bigint (18)
	 */
	public function setBot_role_id($bot_role_id){
		$this->bot_role_id = $bot_role_id;
	}

	/**
	 * @param Type: varchar (255)
	 */
	public function setCapability($capability){
		$this->capability = $capability;
	}

	/**
	 * @param Type: bigint (18)
	 */
	public function setPermission($permission){
		$this->permission = $permission;
	}

	/**
	 * @param Type: bigint (18)
	 */
	public function setUsermodified($usermodified){
		$this->usermodified = $usermodified;
	}

	/**
	 * @param Type: bigint (18)
	 */
	public function setTimecreated($timecreated){
		$this->timecreated = $timecreated;
	}

	/**
	 * @param Type: bigint (18)
	 */
	public function setTimemodified($timemodified){
		$this->timemodified = $timemodified;
	}

}