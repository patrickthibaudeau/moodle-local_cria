<?php
/*
 * Author: Admin User
 * Create Date: 23-12-2023
 * License: LGPL 
 * 
 */

namespace local_cria;

class entities
{

    /**
     *
     * @var string
     */
    private $results;

    /**
     *
     * @global \moodle_database $DB
     */
    public function __construct($bot_id = 0)
    {
        global $DB;
        if ($bot_id == 0) {
            $this->results = [];
        } else {
            $this->results = $DB->get_records('local_cria_entity', ['bot_id' => $bot_id], 'name');
        }
    }


    /**
     * Get records
     */
    public function get_records()
    {
        return $this->results;
    }

    /**
     * Array to be used for selects
     * Defaults used key = record id, value = name
     * Modify as required.
     */
    public function get_select_array()
    {
        $array = [
            '' => get_string('select', 'local_cria')
        ];
        foreach ($this->results as $r) {
            $array[$r->id] = $r->name;
        }
        return $array;
    }

}