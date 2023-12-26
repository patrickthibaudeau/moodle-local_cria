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
use local_cria\intent;

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
    private $intent_id;

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
     * @@var String
     */
    private $lang;

    /**
     * @var String
     */
    private $faculty;

    /**
     * @var String
     */
    private $program;

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
     * @var string
     */
    private $keywords;


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

        $this->intent_id = $result->intent_id ?? 0;
        $this->name = $result->name ?? '';
        $this->content = $result->content ?? '';
        $this->lang = $result->lang ?? '';
        $this->faculty = $result->faculty ?? '';
        $this->program = $result->program ?? '';
        $this->keywords = $result->keywords ?? '';
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
    public function get_intent_id(): int
    {
        return $this->intent_id;
    }

    /**
     * Get bot name based on intent id
     */
    public function get_bot_name(): string
    {
        $INTENT = new intent($this->get_intent_id());
        return $INTENT->get_bot_name();
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
     * @return lang - varchar (255)
     */
    public function get_lang(): string
    {
        return $this->lang;
    }

    /**
     * @return faculty - varchar (255)
     */
    public function get_faculty(): string
    {
        return $this->faculty;
    }

    /**
     * @return program - varchar (255)
     */
    public function get_program(): string
    {
        return $this->program;
    }

    /**
     * @return keywords - varchar (255)
     */
    public function get_keywords(): string
    {
        return $this->keywords;
    }

    /**
     * @return array of keywords
     */
    public function get_keywords_array(): array
    {
        $keywords = json_decode($this->get_keywords());
        return $keywords;
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
        // Create indexes if they don't exist
        base::create_cria_indexes($bot_name);

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