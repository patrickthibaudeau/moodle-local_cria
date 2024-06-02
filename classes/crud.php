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

    //*** Constants ***//
    const FILE_TYPE_DOCX = 'docx';
    const FILE_TYPE_PDF = 'pdf';
    const FILE_TYPE_XLSX = 'xlsx';
    const FILE_TYPE_PPTX = 'pptx';
    const FILE_TYPE_TXT = 'txt';
    const FILE_TYPE_RTF = 'rtf';
    const FILE_TYPE_HTML = 'html';
    const FILE_TYPE_PNG = 'png';
    const FILE_TYPE_JPEG = 'jpeg';

    /**
     * Get record
     *
     * @global \moodle_database $DB
     *
     */
    public function get_record(): \stdClass
    {
        global $DB;
        if ($result = $DB->get_record($this->table, ['id' => $this->id])) {
            return $result;
        }
        $result = new \stdClass();

        return $result;
    }

    /**
     * Delete the row
     *
     * @global \moodle_database $DB
     *
     */
    public function delete_record(): bool
    {
        global $DB;
        if ($DB->delete_records($this->table, ['id' => $this->id])) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Insert record into selected table
     * @param object $data
     * @global \stdClass $USER
     * @global \moodle_database $DB
     */
    public function insert_record($data): int
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
    public function update_record($data): int
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
    public function get_id(): int
    {
        return $this->id;
    }

    /**
     * /* get table
     **/
    public function get_table(): string
    {
        return $this->table;
    }

    /**
     * @param mixed $table
     */
    public function set_table($table): void
    {
        $this->table = $table;
    }

    /**
     * @param int $id
     */
    public function set_id($id): void
    {
        $this->id = $id;
    }

}