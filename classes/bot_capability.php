<?php
/*
 * Author: Admin User
 * Create Date: 21-10-2023
 * License: LGPL 
 * 
 */

namespace local_cria;

class bot_capability extends crud
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
    private $bot_role_id;

    /**
     *
     * @var string
     */
    private $capability;

    /**
     *
     * @var int
     */
    private $permission;

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
            $this->timecreated_hr = strftime(get_string('strftimedate'), $result->timecreated);
        }
        $this->timemodified = $result->timemodified ?? 0;
        $this->timemodified_hr = '';
        if ($this->timemodified) {
            $this->timemodified_hr = strftime(get_string('strftimedate'), $result->timemodified);
        }
    }


    /**
     * @return id - bigint (18)
     */
    public function get_id(): int
    {
        return $this->id;
    }

    /**
     * @return bot_role_id - bigint (18)
     */
    public function getBot_role_id(): int
    {
        return $this->bot_role_id;
    }

    /**
     * @return capability - varchar (255)
     */
    public function get_capability(): string
    {
        return $this->capability;
    }

    /**
     * @return permission - bigint (18)
     */
    public function get_permission(): int
    {
        return $this->permission;
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
     * @param Type: bigint (18)
     */
    public function set_bot_role_id($bot_role_id): void
    {
        $this->bot_role_id = $bot_role_id;
    }

    /**
     * @param Type: varchar (255)
     */
    public function set_capability($capability): void
    {
        $this->capability = $capability;
    }

    /**
     * @param Type: bigint (18)
     */
    public function set_permission($permission): void
    {
        $this->permission = $permission;
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