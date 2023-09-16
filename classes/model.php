<?php
/*
 * Author: Admin User
 * Create Date: 26-08-2023
 * License: LGPL 
 * 
 */

namespace local_cria;

use local_cria\crud;
use local_cria\base;

class model extends crud
{


    /**
     *
     * @var int
     */
    private $id;

    /**
     *
     * @var string
     */
    private $name;

    /**
     *
     * @var string
     */
    private $azure_endpoint;

    /**
     *
     * @var string
     */
    private $azure_api_version;

    /**
     *
     * @var string
     */
    private $azure_key;

    /**
     *
     * @var string
     */
    private $azure_deployment_name;

    /**
     *
     * @var int
     */
    private $is_embedding;

    /**
     *
     * @var string
     */
    private $model_name;

    /**
     *
     * @var string
     */
    private $prompt_cost;

    /**
     *
     * @var string
     */
    private $completion_cost;

    /**
     *
     * @var int
     */
    private $usermodified;

    /**
     *
     * @var int
     */
    private $timecreated;

    /**
     *
     * @var string
     */
    private $timecreated_hr;

    /**
     *
     * @var int
     */
    private $timemodified;

    /**
     *
     * @var string
     */
    private $timemodified_hr;

    /**
     *
     * @var string
     */
    private $table;


    /**
     *
     *
     */
    public function __construct($id = 0)
    {
        global $CFG, $DB, $DB;

        $this->table = 'local_cria_models';

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

        $this->result = $result;

        $this->name = $result->name ?? '';
        $this->azure_endpoint = $result->azure_endpoint ?? '';
        $this->azure_api_version = $result->azure_api_version ?? '';
        $this->azure_key = $result->azure_key ?? '';
        $this->azure_deployment_name = $result->azure_deployment_name ?? '';
        $this->is_embedding = $result->is_embedding ?? 0;
        $this->model_name = $result->model_name ?? '';
        $this->prompt_cost = $result->prompt_cost ?? '';
        $this->completion_cost = $result->completion_cost ?? '';
        $this->usermodified = $result->usermodified ?? 0;
        $this->timecreated = $result->timecreated ?? 0;
        $this->timecreated_hr = '';
        if ($this->timecreated) {
            $this->timecreated_hr = base::strftime(get_string('strftimedate'), $result->timecreated);
        }
        $this->timemodified = $result->timemodified ?? 0;
        $this->timemodified_hr = '';
        if ($this->timemodified) {
            $this->timemodified_hr = base::strftime(get_string('strftimedate'), $result->timemodified);
        }
    }

    /**
     * Return record
     * @return mixed
     */
    public function get_result() {
        return $this->result;
    }

    /**
     * @return id - bigint (18)
     */
    public function get_id()
    {
        return $this->id;
    }

    /**
     * @return name - varchar (255)
     */
    public function get_name()
    {
        return $this->name;
    }

    /**
     * @return azure_endpoint - varchar (255)
     */
    public function get_azure_endpoint()
    {
        return $this->azure_endpoint;
    }

    /**
     * @return azure_api_version - varchar (50)
     */
    public function get_azure_api_version()
    {
        return $this->azure_api_version;
    }

    /**
     * @return azure_key - varchar (1333)
     */
    public function get_azure_key()
    {
        return $this->azure_key;
    }

    /**
     * @return azure_deployment_name - varchar (255)
     */
    public function get_azure_deployment_name()
    {
        return $this->azure_deployment_name;
    }

    /**
     * @return is_embedding - int (1)
     */
    public function get_is_embedding() {
        return $this->is_embedding;
    }

    /**
     * @return model_name - varchar (50)
     */
    public function get_model_name()
    {
        return $this->model_name;
    }

    /**
     * @return prompt_cost - decimal (8)
     */
    public function get_prompt_cost()
    {
        return $this->prompt_cost;
    }

    /**
     * @return completion_cost - decimal (8)
     */
    public function get_completion_cost()
    {
        return $this->completion_cost;
    }

    /**
     * @return usermodified - bigint (18)
     */
    public function get_usermodified()
    {
        return $this->usermodified;
    }

    /**
     * @return timecreated - bigint (18)
     */
    public function get_timecreated()
    {
        return $this->timecreated;
    }

    /**
     * @return timemodified - bigint (18)
     */
    public function get_timemodified()
    {
        return $this->timemodified;
    }

    /**
     * @param Type: bigint (18)
     */
    public function set_id($id)
    {
        $this->id = $id;
    }

    /**
     * @param Type: varchar (255)
     */
    public function set_name($name)
    {
        $this->name = $name;
    }

    /**
     * @param Type: varchar (255)
     */
    public function set_azure_endpoint($azure_endpoint)
    {
        $this->azure_endpoint = $azure_endpoint;
    }

    /**
     * @param Type: varchar (50)
     */
    public function set_azure_api_version($azure_api_version)
    {
        $this->azure_api_version = $azure_api_version;
    }

    /**
     * @param Type: varchar (1333)
     */
    public function set_azure_key($azure_key)
    {
        $this->azure_key = $azure_key;
    }

    /**
     * @param Type: varchar (255)
     */
    public function set_azure_deployment_name($azure_deployment_name)
    {
        $this->azure_deployment_name = $azure_deployment_name;
    }

    /**
     * @param Type: varchar (50)
     */
    public function set_model_name($model_name)
    {
        $this->model_name = $model_name;
    }

    /**
     * @param Type: decimal (8)
     */
    public function set_prompt_cost($prompt_cost)
    {
        $this->prompt_cost = $prompt_cost;
    }

    /**
     * @param Type: decimal (8)
     */
    public function set_completion_cost($completion_cost)
    {
        $this->completion_cost = $completion_cost;
    }

    /**
     * @param Type: bigint (18)
     */
    public function set_usermodified($usermodified)
    {
        $this->usermodified = $usermodified;
    }

    /**
     * @param Type: bigint (18)
     */
    public function set_timecreated($timecreated)
    {
        $this->timecreated = $timecreated;
    }

    /**
     * @param Type: bigint (18)
     */
    public function set_timemodified($timemodified)
    {
        $this->timemodified = $timemodified;
    }

}