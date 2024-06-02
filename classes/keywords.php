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
 * Create Date: 23-12-2023
 * License: LGPL 
 * 
 */

namespace local_cria;

class keywords
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
    public function __construct($entity_id = 0)
    {
        global $DB;

        if ($entity_id == 0) {
            $this->results = [];
        } else {
            $this->results = $DB->get_records('local_cria_keyword', ['entity_id' => $entity_id], 'value');
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

    public function get_keywords_for_criabot($keywords_json)
    {
        global $DB;
        // Get keywords and synonyms
        $keywords = [];
        $keywords_array = json_decode($keywords_json);
        foreach ($keywords_array as $key => $keyword_id) {
            $keyword = $DB->get_record('local_cria_keyword', ['id' => $keyword_id]);
            $keywords[] = $keyword->value;
            // Get synonyms
            $synonyms = $DB->get_records('local_cria_synonyms', ['keyword_id' => $keyword_id]);
            foreach ($synonyms as $synonym) {
                $keywords[] = $synonym->value;
            }
        }
        return $keywords;
    }
}