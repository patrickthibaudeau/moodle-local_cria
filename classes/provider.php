<?php
/*
 * Author: Admin User
 * Create Date: 26-08-2023
 * License: LGPL 
 * 
 */
namespace local_cria;

use local_cria\crud;
use local_cria\base;

class provider extends crud
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
     * @var string
     */
    private $idnumber;

    /**
     *
     * @var string
     */
    private $llm_models;

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

        $this->table = 'local_cria_providers';

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

        $this->name = $result->name ?? '';
        $this->idnumber = $result->idnumber ?? '';
        $this->llm_models = $result->llm_models ?? '';
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
     * @return \local_cria\id - bigint (18)
     */
    public function get_id(): int
    {
        return $this->id;
    }

    /**
     * @return \local_cria\name - varchar (255)
     */
    public function get_name(): string
    {
        return $this->name;
    }

    /**
     * @return \local_cria\idnumber - varchar (255)
     */
    public function get_idnumber(): string
    {
        return $this->idnumber;
    }

    /**
     * @return \local_cria\idnumber - varchar (255)
     */
    public function get_llm_models(): string
    {
        return $this->llm_models;
    }

    /**
     * return array of llm models
     */
    public function get_llm_models_array(): array
    {
        $llm_models = explode("\n", $this->llm_models);
        $models = [];
        foreach ($llm_models as $key => $value) {
            $models[trim($value)] = trim($value);
        }
        return $models;
    }

    /**
     * @return string
     * @throws \coding_exception
     * @throws \dml_exception
     */
    public function get_image(): string {
        $context = \context_system::instance();
        $fs = get_file_storage();
        $files = $fs->get_area_files($context->id, 'local_cria', 'provider', $this->id);
        foreach($files as $f) {
            if ($f->get_filename() != '.') {
                $file_name = trim($f->get_filename());
                break;
            }
        }
        $url = \moodle_url::make_pluginfile_url(
            $context->id,
            'local_cria',
            'provider',
            $this->id,
            '/',
            $file_name,
            false
        );
        $image_url = $url->out();

        return $image_url;
    }

    /**
     * @return \local_cria\usermodified - bigint (18)
     */
    public function get_usermodified(): int
    {
        return $this->usermodified;
    }

    /**
     * @return \local_cria\usermodified - bigint (18)
     */
    public function get_usermodified_fullname(): string
    {
        global $DB;
        $user = $DB->get_record('user', ['id' => $this->usermodified]);
        return fullname($user);
    }

    /**
     * @return \local_cria\timecreated - bigint (18)
     */
    public function get_timecreated(): int
    {
        return $this->timecreated;
    }

    /**
     * @return \local_cria\timemodified - bigint (18)
     */
    public function get_timemodified(): int
    {
        return $this->timemodified;
    }

    /**
     * @param \local_cria\Type: bigint (18)
     */
    public function set_id($id): void
    {
        $this->id = $id;
    }

    /**
     * @param \local_cria\name: varchar (255)
     */
    public function set_name($name): void
    {
        $this->name = $name;
    }

    /**
     * @param \local_cria\idnumber: varchar (255)
     */
    public function set_idnumber($idnumber): void
    {
        $this->idnumber = $idnumber;
    }

    /**
     * @param \local_cria\Type: bigint (18)
     */
    public function set_usermodified($usermodified): void
    {
        $this->usermodified = $usermodified;
    }

    /**
     * @param \local_cria\Type: bigint (18)
     */
    public function set_timecreated($timecreated): void
    {
        $this->timecreated = $timecreated;
    }

    /**
     * @param \local_cria\Type: bigint (18)
     */
    public function set_timemodified($timemodified): void
    {
        $this->timemodified = $timemodified;
    }

}