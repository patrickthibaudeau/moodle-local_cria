<?php
/*
 * Author: Admin User
 * Create Date: 27-07-2023
 * License: LGPL 
 * 
 */

namespace local_cria;

use local_cria\crud;
use local_cria\bot;
use local_cria\files;
use local_cria\criabot;

class file extends crud
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
    private $bot_id;

    /**
     *
     * @var string
     */
    private $name;

    /**
     *
     * @var string
     */
    private $content;

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

        $this->table = 'local_cria_files';

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

        $this->bot_id = $result->bot_id ?? 0;
        $this->name = $result->name ?? '';
        $this->content = $result->content ?? '';
        $this->usermodified = $result->usermodified ?? 0;
        $this->timecreated = $result->timecreated ?? 0;
        $this->timemodified = $result->timemodified ?? 0;

    }

    /**
     * @return id - bigint (18)
     */
    public function get_id(): int
    {
        return $this->id;
    }

    /**
     * @return bot_id - bigint (18)
     */
    public function get_bot_id(): int
    {
        return $this->bot_id;
    }

    /**
     * @return name - varchar (255)
     */
    public function get_name(): string
    {
        return $this->name;
    }

    /**
     * @return content - longtext (-1)
     */
    public function get_content(): string
    {
        return $this->content;
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
    public function set_bot_id($bot_id): void
    {
        $this->bot_id = $bot_id;
    }

    /**
     * @param Type: varchar (255)
     */
    public function set_name($name): void
    {
        $this->name = $name;
    }

    /**
     * @param Type: longtext (-1)
     */
    public function set_content($content): void
    {
        $this->content = $content;
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

    /**
     * @param $bot_name
     * @param $file_path
     * @param $file_name
     * @param $update
     * @return true
     */
    public function upload_files_to_indexing_server($bot_name, $file_path, $file_name, $update = false)
    {
        // Check to see that the document index exists in Criadex
        $document_index = criadex::index_about($bot_name . '-document-index');
        $question_index = criadex::index_about($bot_name . '-question-index');
        $cache_index = criadex::index_about($bot_name . '-cache-index');
        // If document index does not exist, create it
        if ($document_index->status == 404) {
            // Get bot parameters by splitting $bot_name
            $bot_intent_ids = explode('-', $bot_name);
            $BOT = new bot($bot_intent_ids[0]);
            $bot_parameters = json_decode($BOT->get_bot_parameters_json());
            // Create index
            $index = criadex::index_create(
                $bot_name . '-document-index',
                $bot_parameters->llm_model_id,
                $bot_parameters->embedding_model_id,
                'DOCUMENT'
            );
        }

        // If question index does not exist, create it
        if ($question_index->status == 404) {
            // Get bot parameters by splitting $bot_name
            $bot_intent_ids = explode('-', $bot_name);
            $BOT = new bot($bot_intent_ids[0]);
            $bot_parameters = json_decode($BOT->get_bot_parameters_json());
            // Create index
            $index = criadex::index_create(
                $bot_name . '-question-index',
                $bot_parameters->llm_model_id,
                $bot_parameters->embedding_model_id,
                'QUESTION'
            );
        }

        // if cache index does not exist, create it
        if ($cache_index->status == 404) {
            // Get bot parameters by splitting $bot_name
            $bot_intent_ids = explode('-', $bot_name);
            $BOT = new bot($bot_intent_ids[0]);
            $bot_parameters = json_decode($BOT->get_bot_parameters_json());
            // Create index
            $index = criadex::index_create(
                $bot_name . '-cache-index',
                $bot_parameters->llm_model_id,
                $bot_parameters->embedding_model_id,
                'CACHE'
            );
        }

        if ($update) {
            // update file
            return criabot::document_update($bot_name, $file_path, $file_name);
        } else {
            // upload new file
            return criabot::document_create($bot_name, $file_path, $file_name);
        }
    }

    /**
     * Get file name for file on indexing server
     * @return string
     */
    public function get_indexing_server_file_name(): string
    {
        $file_name = $this->get_name() . '_' . $this->get_cria_id() . '.txt';
        $file_name = str_replace(' ', '_', $file_name);
        $file_name = str_replace('(', '_', $file_name);
        $file_name = str_replace(')', '_', $file_name);

        return strtolower($file_name);
    }


}