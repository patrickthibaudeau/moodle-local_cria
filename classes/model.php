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
    public function get_result(): \stdClass {
        return $this->result;
    }

    /**
     * @return id - bigint (18)
     */
    public function get_id(): int
    {
        return $this->id;
    }

    /**
     * @return name - varchar (255)
     */
    public function get_name(): string
    {
        return $this->name;
    }

    /**
     * @return azure_endpoint - varchar (255)
     */
    public function get_azure_endpoint(): string
    {
        return $this->azure_endpoint;
    }

    /**
     * @return azure_api_version - varchar (50)
     */
    public function get_azure_api_version(): string
    {
        return $this->azure_api_version;
    }

    /**
     * @return azure_key - varchar (1333)
     */
    public function get_azure_key(): string
    {
        return $this->azure_key;
    }

    /**
     * @return azure_deployment_name - varchar (255)
     */
    public function get_azure_deployment_name(): string
    {
        return $this->azure_deployment_name;
    }

    /**
     * @return is_embedding - int (1)
     */
    public function get_is_embedding(): int
    {
        return $this->is_embedding;
    }

    /**
     * @return model_name - varchar (50)
     */
    public function get_model_name(): string
    {
        return $this->model_name;
    }

    /**
     * @return prompt_cost - decimal (8)
     */
    public function get_prompt_cost(): string
    {
        return $this->prompt_cost;
    }

    /**
     * @return completion_cost - decimal (8)
     */
    public function get_completion_cost(): string
    {
        return $this->completion_cost;
    }

    /**
     * @return usermodified - bigint (18)
     */
    public function get_usermodified(): int
    {
        return $this->usermodified;
    }

    /**
     * @return timecreated - bigint (18)
     */
    public function get_timecreated(): int
    {
        return $this->timecreated;
    }

    /**
     * @return timemodified - bigint (18)
     */
    public function get_timemodified(): int
    {
        return $this->timemodified;
    }

    /**
     * @param Type: bigint (18)
     */
    public function set_id($id): void
    {
        $this->id = $id;
    }

    /**
     * @param Type: varchar (255)
     */
    public function set_name($name): void
    {
        $this->name = $name;
    }

    /**
     * @param Type: varchar (255)
     */
    public function set_azure_endpoint($azure_endpoint): void
    {
        $this->azure_endpoint = $azure_endpoint;
    }

    /**
     * @param Type: varchar (50)
     */
    public function set_azure_api_version($azure_api_version): void
    {
        $this->azure_api_version = $azure_api_version;
    }

    /**
     * @param Type: varchar (1333)
     */
    public function set_azure_key($azure_key): void
    {
        $this->azure_key = $azure_key;
    }

    /**
     * @param Type: varchar (255)
     */
    public function set_azure_deployment_name($azure_deployment_name): void
    {
        $this->azure_deployment_name = $azure_deployment_name;
    }

    /**
     * @param Type: varchar (50)
     */
    public function set_model_name($model_name): void
    {
        $this->model_name = $model_name;
    }

    /**
     * @param Type: decimal (8)
     */
    public function set_prompt_cost($prompt_cost): void
    {
        $this->prompt_cost = $prompt_cost;
    }

    /**
     * @param Type: decimal (8)
     */
    public function set_completion_cost($completion_cost): void
    {
        $this->completion_cost = $completion_cost;
    }

    /**
     * @param Type: bigint (18)
     */
    public function set_usermodified($usermodified): void
    {
        $this->usermodified = $usermodified;
    }

    /**
     * @param Type: bigint (18)
     */
    public function set_timecreated($timecreated): void
    {
        $this->timecreated = $timecreated;
    }

    /**
     * @param Type: bigint (18)
     */
    public function set_timemodified($timemodified): void
    {
        $this->timemodified = $timemodified;
    }

}