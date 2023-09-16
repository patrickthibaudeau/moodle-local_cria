<?php
/*
 * Author: Admin User
 * Create Date: 27-07-2023
 * License: LGPL 
 * 
 */
namespace local_cria;

use local_cria\crud;
use local_cria\bot;
use local_cria\files;
use local_cria\cria;

class file extends crud {


	/**
	 *
	 *@var int
	 */
	private $id;

	/**
	 *
	 *@var int
	 */
	private $bot_id;

	/**
	 *
	 *@var string
	 */
	private $name;

	/**
	 *
	 *@var string
	 */
	private $content;

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

		$this->table = 'local_cria_files';

		parent::set_table($this->table);

      if ($id) {
         $this->id = $id;
         parent::set_id($this->id);
         $result = $this->get_record($this->table, $this->id);
      } else {
        $result = new \stdClass();
         $this->id = 0;
         parent::set_id($this->id);
      }

		$this->bot_id = $result->bot_id ?? 0;
		$this->name = $result->name ?? '';
		$this->content = $result->content ?? '';
		$this->usermodified = $result->usermodified ?? 0;
		$this->timecreated = $result->timecreated ?? 0;
		$this->timemodified = $result->timemodified ?? 0;

	}

	/**
	 * @return id - bigint (18)
	 */
	public function get_id(){
		return $this->id;
	}

	/**
	 * @return bot_id - bigint (18)
	 */
	public function get_cria_id(){
		return $this->bot_id;
	}

	/**
	 * @return name - varchar (255)
	 */
	public function get_name(){
		return $this->name;
	}

	/**
	 * @return content - longtext (-1)
	 */
	public function get_content(){
		return $this->content;
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
	 * @param Type: bigint (18)
	 */
	public function set_bot_id($bot_id){
		$this->bot_id = $bot_id;
	}

	/**
	 * @param Type: varchar (255)
	 */
	public function set_name($name){
		$this->name = $name;
	}

	/**
	 * @param Type: longtext (-1)
	 */
	public function set_content($content){
		$this->content = $content;
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

    /**
     * Upload files to indexing server
     * @param $bot_id
     * @return void
     */
    public function upload_files_to_indexing_server($bot_id, $file_path, $file_name) {

        // upload new file
        return cria::add_file($bot_id, $file_path, $file_name);
    }

    /**
     * Get file name for file on indexing server
     * @return string
     */
    public function get_indexing_server_file_name() {
        $file_name = $this->get_name() . '_' . $this->get_cria_id() . '.txt';
        $file_name = str_replace(' ', '_', $file_name);
        $file_name = str_replace('(', '_', $file_name);
        $file_name = str_replace(')', '_', $file_name);

        return strtolower($file_name);
    }


}