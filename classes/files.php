<?php
/*
 * Author: Admin User
 * Create Date: 27-07-2023
 * License: LGPL 
 * 
 */
namespace local_cria;
use local_cria\bot;
use local_cria\intent;
use local_cria\criaparse;
use local_cria\file;

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
        $PARSER = new criaparse();
        $FILE = new file();
        $BOT = new bot($INTENT->get_bot_id());
        $bot_name = $INTENT->get_bot_name();
        // set bot parsing strategy
        $bot_parsing_strategy = $BOT->get_parse_strategy();

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
                // Set parsing strategy based on file type.
                $parsing_strategy = $PARSER->set_parsing_strategy_based_on_file_type($file->get_mimetype(), $bot_parsing_strategy);
                $results = $PARSER->execute($parsing_strategy, $file_path . '/' . $file->get_filename());
                // Delete file from CriaBot
                criabot::document_delete($bot_name, $file->get_filename());
                if ($results['status'] != 200) {
                    \core\notification::error('Error parsing file: ' . $results['message']);
                } else {
                    $nodes = $results['nodes'];
                    // Send nodes to indexing server
                    $upload = $FILE->upload_nodes_to_indexing_server($bot_name, $nodes, $file->get_filename(), $FILE->get_file_type_from_mime_type($file->get_mimetype()), false);
                    if ($upload->status != 200) {
                        \core\notification::error('Error uploading file to indexing server: ' . $upload->message);
                    }
                }

                unlink($file_path . '/' . $file->get_filename());
            }
        }
    }
}