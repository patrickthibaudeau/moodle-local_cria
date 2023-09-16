<?php
/*
 * Author: Admin User
 * Create Date: 27-07-2023
 * License: LGPL 
 * 
 */

namespace local_cria;

use local_cria\cria_type;
use local_cria\cria;

abstract class crud
{


    /**
     * /* string
     **/
    private $table;

    /**
     * /* int
     **/
    private $id;

    /**
     * Get record
     *
     * @global \moodle_database $DB
     *
     */
    public function get_record()
    {
        global $DB;
        $result = $DB->get_record($this->table, ['id' => $this->id]);
        return $result;

    }

    /**
     * Delete the row
     *
     * @global \moodle_database $DB
     *
     */
    public function delete_record()
    {
        global $DB;
        $DB->delete_records($this->table, ['id' => $this->id]);
    }

    /**
     * Insert record into selected table
     * @param object $data
     * @global \stdClass $USER
     * @global \moodle_database $DB
     */
    public function insert_record($data)
    {
        global $DB, $USER;

        if (!isset($data->timecreated)) {
            $data->timecreated = time();
        }

        if (!isset($data->imemodified)) {
            $data->timemodified = time();
        }

        //Set user
        $data->usermodified = $USER->id;

        $id = $DB->insert_record($this->table, $data);

        return $id;
    }

    /**
     * Update record into selected table
     * @param object $data
     * @global \stdClass $USER
     * @global \moodle_database $DB
     */
    public function update_record($data)
    {
        global $DB, $USER;

        if (!isset($data->timemodified)) {
            $data->timemodified = time();
        }

        //Set user
        $data->usermodified = $USER->id;

        $id = $DB->update_record($this->table, $data);

        return $id;
    }

    /**
     * /* get id
     **/
    public function get_id()
    {
        return $this->id;
    }

    /**
     * /* get table
     **/
    public function get_table()
    {
        return $this->table;
    }

    /**
     * @param mixed $table
     */
    public function set_table($table)
    {
        $this->table = $table;
    }

    /**
     * @param mixed $table
     */
    public function set_id($id)
    {
        $this->id = $id;
    }

}