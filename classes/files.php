<?php
/*
 * Author: Admin User
 * Create Date: 27-07-2023
 * License: LGPL 
 * 
 */
namespace local_cria;
use local_cria\bot;

class files {

	/**
	 *
	 *@var string
	 */
	private $results;

    /**
     * @var string
     */
    private $intent_idd;

	/**
	 *
	 *@global \moodle_database $DB
	 */
	public function __construct($intent_id) {
	    global $DB;
        $this->intent_id = $intent_id;
	    $this->results = $DB->get_records('local_cria_files', array('intent_id' => $intent_id));
	}

	/**
	  * Get records
	 */
	public function get_records() {
	    return $this->results;
	}

    public function concatenate_content() {
        $content = '';
        foreach($this->results as $r) {
            $content .= $r->content;
        }
        return $content;
    }

	/**
	  * Array to be used for selects
	  * Defaults used key = record id, value = name 
	  * Modify as required. 
	 */
	public function get_select_array() {
	    $array = [
	        '' => get_string('select', 'local_cria')
	      ];
	      foreach($this->results as $r) {
	            $array[$r->id] = $r->name;
	      }
	    return $array;
	}

    public function get_bot_name() {
        $BOT = new bot($this->bot_id);
        return $BOT->get_name();
    }

    /**
     * Publish all files to CriaBot
     */
    public function publish_all_files() {
        global $CFG;
        $INTENT = new intent($this->intent_id);
        $bot_name = $INTENT->get_bot_name();
        // Set context
        $context = \context_system::instance();
        // Get all filearea files for the intent
        $fs = get_file_storage();
        $files = $fs->get_area_files(
            $context->id,
            'local_cria',
            'content',
            $this->intent_id
        );

        if (!is_dir($CFG->dataroot . '/temp/cria')) {
            mkdir($CFG->dataroot . '/temp/cria');
        }
        if (!is_dir($CFG->dataroot . '/temp/cria/' . $this->intent_id)) {
            mkdir($CFG->dataroot . '/temp/cria/' . $this->intent_id);
        }
        $file_path = $CFG->dataroot . '/temp/cria/' . $this->intent_id ;
        foreach ($files as $file) {
            if ($file->get_filename() != '.' && $file->get_filename() != '') {
                $file->copy_content_to($file_path . '/' . $file->get_filename());
                // Delete file from CriaBot
                criabot::document_delete($bot_name, $file->get_filename());
                // Create file on CriaBot
                criabot::document_create($bot_name, $file_path . '/' . $file->get_filename(), $file->get_filename());
                unlink($file_path . '/' . $file->get_filename());
            }
        }
    }
}