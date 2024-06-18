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

use local_cria\bot;
use local_cria\intent;
use local_cria\criaparse;
use local_cria\file;

class files
{

    /**
     *
     * @var string
     */
    private $results;

    /**
     * @var string
     */
    private $intent_idd;

    /**
     *
     * @global \moodle_database $DB
     */
    public function __construct($intent_id)
    {
        global $DB;
        $this->intent_id = $intent_id;
        $this->results = $DB->get_records('local_cria_files', array('intent_id' => $intent_id));
    }

    /**
     * Get records
     */
    public function get_records()
    {
        return $this->results;
    }

    public function concatenate_content()
    {
        $content = '';
        foreach ($this->results as $r) {
            $content .= $r->content;
        }
        return $content;
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

    public function get_bot_name()
    {
        $BOT = new bot($this->bot_id);
        return $BOT->get_name();
    }

    /**
     * Publish all files to CriaBot
     */
    public function publish_all_files()
    {
        global $CFG, $DB;

        // Create a new instance of the Intent class with the current intent_id
        $INTENT = new intent($this->intent_id);
        // Get all files for intent
        $files = $DB->get_records('local_cria_files', ['intent_id' => $this->intent_id]);
        // loop through files and publish them
        foreach ($files as $file) {
            $this->publish_file($file->id);
        }
    }


    /**
     * @param $file_id
     * @return void
     * @throws \coding_exception
     * @throws \dml_exception
     */
    public function publish_file($file_id)
    {
        global $CFG, $DB;
        // Get teh file record
        $file_to_publish = $DB->get_record('local_cria_files', ['id' => $file_id]);
        // Create a new instance of the Intent class with the current intent_id
        $INTENT = new intent($file_to_publish->intent_id);
        // Create a new instance of the Criaparse class
        $PARSER = new criaparse();
        // Create a new instance of the File class
        $FILE = new file();
        // Create a new instance of the Bot class with the bot_id from the current intent
        $BOT = new bot($INTENT->get_bot_id());
        // Get the bot name from the current intent
        $bot_name = $INTENT->get_bot_name();
        // set bot parsing strategy
        $bot_parsing_strategy = $BOT->get_parse_strategy();
        // Set status
        $status = new \stdClass();

        // Set context
        $context = \context_system::instance();

        // Does the content field contain a URL?
        if (filter_var($file_to_publish->content, FILTER_VALIDATE_URL)) {
            $urls = [$file_to_publish->content];
            $status = $this->publish_urls($urls);
            return $status;
        } else {
            // Get all filearea files for the intent
            $fs = get_file_storage();

            $file = $fs->get_file(
                $context->id,
                'local_cria',
                'content',
                $file_to_publish->intent_id,
                '/',
                $file_to_publish->name
            );

            if (!$file) {
                $status->status = 404;
                $status->message = 'File not found: ' . $file_to_publish->name;
                $status->id = $file_id;
                $status->file = $file_to_publish->name;
                return $status;
            }

            base::create_directory_if_not_exists($CFG->dataroot . '/temp/cria');
            base::create_directory_if_not_exists($CFG->dataroot . '/temp/cria/' . $this->intent_id);

            $file_path = $CFG->dataroot . '/temp/cria/' . $this->intent_id;
            // Copy file content to temp folder
            $file->copy_content_to($file_path . '/' . $file->get_filename());
            // Set parsing strategy based on file type.
            $parsing_strategy = $PARSER->set_parsing_strategy_based_on_file_type(
                $file->get_mimetype(),
                $bot_parsing_strategy
            );
            // Get parsed results
            $results = $PARSER->execute(
                $BOT->get_model_id(),
                $BOT->get_embedding_id(),
                $parsing_strategy,
                $file_path . '/' . $file->get_filename()
            );
            // Delete file from CriaBot
            criabot::document_delete($bot_name, $file->get_filename());
            if ($results['status'] != 200) {
                \core\notification::error('Error parsing file: ' . $results['message']);
            } else {
                // Get nodes from parsed results
                $nodes = $results['nodes'];
                // Send nodes to indexing server
                $upload = $FILE->upload_nodes_to_indexing_server($bot_name, $nodes, $file->get_filename(), $FILE->get_file_type_from_mime_type($file->get_mimetype()), false);
                if ($upload->status != 200) {
                    \core\notification::error('Error uploading file to indexing server: ' . $upload->message);
                }
            }

            unlink($file_path . '/' . $file->get_filename());
            // Unset all class instances
            unset($INTENT);
            unset($PARSER);
            unset($FILE);
            unset($BOT);

            $status->status = 200;
            $status->message = 'File published: ' . $file_to_publish->name;
            $status->id = $file_id;
            $status->file = $file_to_publish->name;
        }
    }

    /**
     * Publish urls to CriaBot
     * @param array $urls
     */
    public function publish_urls($urls = [])
    {
        global $CFG, $DB, $USER;
        // Get local cria config
        $config = get_config('local_cria');
        // Create a new instance of the Intent class with the current intent_id
        $INTENT = new intent($this->intent_id);
        // Create a new instance of the Criaparse class
        $PARSER = new criaparse();
        // Create a new instance of the File class
        $FILE = new file();
        // Create a new instance of the Bot class with the bot_id from the current intent
        $BOT = new bot($INTENT->get_bot_id());
        // Get the bot name from the current intent
        $bot_name = $INTENT->get_bot_name();
        // set bot parsing strategy
        $bot_parsing_strategy = $BOT->get_parse_strategy();
        // Set fiel type
        $file_type = 'html';
        $file_was_converted = false;
        // Set file system
        $fs = get_file_storage();
        //Set status
        $status = new \stdClass();

        $context = \context_system::instance();

        $content_data = [
            'intent_id' => $this->intent_id,
            'keywords' => '',
            'usermodified' => $USER->id,
            'timemodified' => time(),
        ];
        $errors = '';

        foreach ($urls as $url) {
            // Get the content of the url using curl
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.3');

            $content = curl_exec($ch);

            if (curl_errno($ch)) {
                echo 'Curl error: ' . curl_error($ch);
            }

            curl_close($ch);

            // Clean up HTML content
            $content = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', "", $content);
            $content = preg_replace('/<style\b[^>]*>(.*?)<\/style>/is', "", $content);
            $content = preg_replace('/<link\b[^>]*>(.*?)<\/link>/is', "", $content);
            $content = preg_replace('/<meta\b[^>]*>(.*?)<\/meta>/is', "", $content);
            $content = preg_replace('/<noscript\b[^>]*>(.*?)<\/noscript>/is', "", $content);
            $content = preg_replace('/<iframe\b[^>]*>(.*?)<\/iframe>/is', "", $content);
            $content = preg_replace('/<img\b[^>]*>(.*?)<\/img>/is', "", $content);
            $content = preg_replace('/<svg\b[^>]*>(.*?)<\/svg>/is', "", $content);
            $content = preg_replace('/<form\b[^>]*>(.*?)<\/form>/is', "", $content);
            $content = preg_replace('/<input\b[^>]*>(.*?)<\/input>/is', "", $content);
            $content = preg_replace('/<button\b[^>]*>(.*?)<\/button>/is', "", $content);
            $content = preg_replace('/<select\b[^>]*>(.*?)<\/select>/is', "", $content);
            $content = preg_replace('/<textarea\b[^>]*>(.*?)<\/textarea>/is', "", $content);
            $content = preg_replace('/<audio\b[^>]*>(.*?)<\/audio>/is', "", $content);
            $content = preg_replace('/<video\b[^>]*>(.*?)<\/video>/is', "", $content);
            $content = preg_replace('/<embed\b[^>]*>(.*?)<\/embed>/is', "", $content);
            $content = preg_replace('/<object\b[^>]*>(.*?)<\/object>/is', "", $content);
            $content = preg_replace('/<applet\b[^>]*>(.*?)<\/applet>/is', "", $content);
            $content = preg_replace('/<frame\b[^>]*>(.*?)<\/frame>/is', "", $content);
            $content = preg_replace('/<frameset\b[^>]*>(.*?)<\/frameset>/is', "", $content);
            $content = preg_replace('/<link\b[^>]*>/is', "", $content);

            // Save into an html file with filename based on the url while removing thhp:// or https://
            $file_name = str_replace(['http://', 'https://'], '', $url);
            $file_name = preg_replace('/[^a-zA-Z0-9]/', ' ', $file_name);
            // Check to see if folder temp/cria exists. If not, create it.
            if (!is_dir($CFG->dataroot . '/temp/cria')) {
                mkdir($CFG->dataroot . '/temp/cria');
            }
            // Check to see if folder temp/cria/intent_id exists. If not, create it.
            if (!is_dir($CFG->dataroot . '/temp/cria/' . $this->intent_id)) {
                mkdir($CFG->dataroot . '/temp/cria/' . $this->intent_id);
            }
            $path = $CFG->dataroot . '/temp/cria/' . $this->intent_id . '/';
            $file_name = $file_name . '.html';
            file_put_contents($path . $file_name, $content);

            // if convertapi_api_key is set convert file to docx
            if ($config->convertapi_api_key) {
                $original_file_path = $path . $file_name;
                $FILE->convert_file_to_docx($path, $file_name, 'html');
                $file_name = str_replace('.html', '.docx', $file_name);
                $file_type = 'docx';
                $file_was_converted = true;
            }

            // Save converted file to moodle
            if ($file_was_converted) {
                $fileinfo = [
                    'contextid' => $context->id,   // ID of the context.
                    'component' => 'local_cria', // Your component name.
                    'filearea' => 'content',       // Usually = table name.
                    'itemid' => $this->intent_id,              // Usually = ID of row in table.
                    'filepath' => '/',            // Any path beginning and ending in /.
                    'filename' => $file_name,   // Any filename.
                ];
                // Get file first
                $files = $fs->get_area_files($context->id, 'local_cria', 'content', $this->intent_id);
                foreach ($files as $area_file) {
                    // if file with $file_name already exists, delete it
                    if ($area_file->get_filename() == $file_name) {
                        $area_file->delete();
                    }
                }
                // Create file from pathname
                $fs->create_file_from_pathname($fileinfo, $path . $file_name);
            }
            // Parse the file
            $parsing_strategy = $PARSER->set_parsing_strategy_based_on_file_type(
                $file_type,
                $bot_parsing_strategy
            );
            $results = $PARSER->execute(
                $BOT->get_model_id(),
                $BOT->get_embedding_id(),
                $parsing_strategy,
                $path . $file_name
            );

            if ($results['status'] != 200) {
                $errors .= 'Error parsing file: ' . $results['message'] . '<br>';
            } else {
                $nodes = $results['nodes'];
                // set variable to update nodes
                $update_nodes = false;

                // Verification paramaters
                $content_verification = [
                    'name' => $file_name,
                    'intent_id' => $this->intent_id
                ];
                // get existing record
                $record = $DB->get_record('local_cria_files', $content_verification);
                // If record exists, set update_nodes to true
                if ($record) {
                    $update_nodes = true;
                }
                // Send nodes to indexing server
                $upload = $FILE->upload_nodes_to_indexing_server($bot_name, $nodes, $file_name, $file_type, $update_nodes);
                if ($upload->status != 200) {
                    $errors .= 'Error uploading file to indexing server: ' . $upload->message . '<br>';
                } else {
                    $content_data['name'] = $file_name;

                    if (!$record) {
                        $record = new \stdClass();
                        // Insert the content into the database
                        $content_data['content'] = $url;
                        $content_data['timecreated'] = time();
                        $record->id  = $DB->insert_record('local_cria_files', $content_data);
                    } else {
                        // Update the content into the database
                        $content_data['id'] = $record->id;
                        $content_data['content'] = $url;
                        $DB->update_record('local_cria_files', $content_data);
                    }
                }
            }
            // Delete temp files
            unlink($path . $file_name);
            unlink($original_file_path);

            if (!empty($errors)) {
                $status->status = 404;
                $status->message = $errors;
            } else {
                $status->status = 200;
                $status->message = 'URLs published successfully';
                $status->id = $record->id;
                $status->file = $file_name;

            }
        }
        return $status;
    }
}