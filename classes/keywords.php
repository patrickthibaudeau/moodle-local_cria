<?php
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