<?php

/**
* This file is part of Cria.
* Cria is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.
* Cria is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
* You should have received a copy of the GNU General Public License along with Cria. If not, see <https://www.gnu.org/licenses/>.
*
* @package    local_cria
* @author     Patrick Thibaudeau
* @copyright  2024 onwards York University (https://yorku.ca)
* @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
*/


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
     * @var int
     */
    private $provider_id;

    /**
     * @var string
     */
    private $value;

    /**
     * var int
     */
    private $criadex_model_id;

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
    private $max_tokens;

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
        $this->provider_id = $result->provider_id ?? 0;
        $this->name = $result->name ?? '';
        $this->value = $result->value ?? '';
        $this->max_tokens = $result->max_tokens ?? 0;
        $this->criadex_model_id = $result->criadex_model_id ?? 0;
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
* @return provider_id - bigint (18)
*/
    public function get_provider_id(): int
    {
        return $this->provider_id;
    }

    /**
     * @return name - varchar (255)
     */
    public function get_name(): string
    {
        return $this->name;
    }

    /**
     * @return azure_endpoint - text
     */
    public function get_value(): string
    {
        return $this->value;
    }

    /**
     * @return max_tokens - bigint (18)
     */
    public function get_max_tokens(): int
    {
        return $this->max_tokens;
    }

    /**
     * @return criadex_model_id - bigint (18)
     */
    public function get_criadex_model_id(): int
    {
        return $this->criadex_model_id;
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

    public function get_provider_name(): string
    {
        $PROVIDER = new provider($this->provider_id);
        return $PROVIDER->get_name();
    }

    public function get_provider_idnumber(): string
    {
        $PROVIDER = new provider($this->provider_id);
        return $PROVIDER->get_idnumber();
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
     * @param Type: text
     */
    public function set_idnumber($idnumber): void
    {
        $this->idnumber = $idnumber;
    }

    /**
     * @param Type: varchar (255)
     */
    public function set_name($name): void
    {
        $this->name = $name;
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