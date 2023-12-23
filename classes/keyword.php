<?php
/*
 * Author: Admin User
 * Create Date: 23-12-2023
 * License: LGPL 
 * 
 */

namespace local_cria;

use local_cria\crud;

class keyword extends crud
{
    /**
     *
     * @var int
     */
    private $id;

    /**
     *
     * @var int
     */
    private $entity_id;

    /**
     *
     * @var string
     */
    private $value;

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

        $this->table = 'local_cria_keyword';

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

        $this->entity_id = $result->entity_id ?? 0;
        $this->value = $result->value ?? '';
        $this->usermodified = $result->usermodified ?? 0;
        $this->timecreated = $result->timecreated ?? 0;
        $this->timecreated_hr = '';
        if ($this->timecreated) {
            $this->timecreated_hr = strftime(get_string('strftimedate'), $result->timecreated);
        }
        $this->timemodified = $result->timemodified ?? 0;
        $this->timemodified_hr = '';
        if ($this->timemodified) {
            $this->timemodified_hr = strftime(get_string('strftimedate'), $result->timemodified);
        }
    }

    /**
     * @return id - bigint (19)
     */
    public function get_id(): int
    {
        return $this->id;
    }

    /**
     * @return entity_id - bigint (19)
     */
    public function get_entity_id(): int
    {
        return $this->entity_id;
    }

    /**
     * @return value - varchar (255)
     */
    public function get_value(): string
    {
        return $this->value;
    }

    /**
     * @return usermodified - bigint (19)
     */
    public function get_usermodified(): int
    {
        return $this->usermodified;
    }

    /**
     * @return timecreated - bigint (19)
     */
    public function get_timecreated(): int
    {
        return $this->timecreated;
    }

    /**
     * @return timemodified - bigint (19)
     */
    public function get_timemodified(): int
    {
        return $this->timemodified;
    }

    /**
     * @param Type: bigint (19)
     */
    public function set_id($id): void
    {
        $this->id = $id;
    }

    /**
     * @param Type: bigint (19)
     */
    public function set_entity_id($entity_id): void
    {
        $this->entity_id = $entity_id;
    }

    /**
     * @param Type: varchar (255)
     */
    public function set_value($value): void
    {
        $this->value = $value;
    }

    /**
     * @param Type: bigint (19)
     */
    public function set_usermodified($usermodified): void
    {
        $this->usermodified = $usermodified;
    }

    /**
     * @param Type: bigint (19)
     */
    public function set_timecreated($timecreated): void
    {
        $this->timecreated = $timecreated;
    }

    /**
     * @param Type: bigint (19)
     */
    public function set_timemodified($timemodified): void
    {
        $this->timemodified = $timemodified;
    }

}