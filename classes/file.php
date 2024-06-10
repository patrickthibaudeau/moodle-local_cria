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
     *
     * @var string
     */
    private $file_type;


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
        $this->file_type = $result->file_type ?? '';
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
     * @return file_type - varchar (255)
     */
    public function get_file_type(): string
    {
        return $this->file_type;
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
     * @param $mime_type
     * @return string file_type
     */
    public function get_file_type_from_mime_type($mime_type): string
    {
        switch ($mime_type) {
            case 'application/vnd.openxmlformats-officedocument.wordprocessingml.document':
                $file_type = 'docx';
                break;
            case 'application/msword':
                $file_type = 'doc';
                break;
            case 'application/pdf':
                $file_type = 'pdf';
                break;
            case 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet':
                $file_type = 'xlsx';
                break;
            case 'application/vnd.openxmlformats-officedocument.presentationml.presentation':
                $file_type = 'pptx';
                break;
            case 'text/plain':
                $file_type = 'txt';
                break;
            case 'text/rtf':
                $file_type = 'rtf';
                break;
            case 'text/html':
                $file_type = 'html';
                break;
            case 'image/png':
                $file_type = 'png';
                break;
            case 'image/jpeg':
                $file_type = 'jpeg';
                break;
        }
        return $file_type;
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
        if ($update) {
            // update file
            return criabot::document_update($bot_name, $file_path, $file_name);
        } else {
            // upload new file
            return criabot::document_create($bot_name, $file_path, $file_name);
        }
    }

    /**
     * @param $bot_name
     * @param $nodes
     * @param $file_name
     * @param $update
     * @return true
     */
    public function upload_nodes_to_indexing_server($bot_name, $nodes, $file_name, $file_type, $update = false)
    {
        if ($update) {
            // update file
            return criabot::document_update($bot_name, $nodes, $file_name, $file_type, true);
        } else {
            // upload new file
            return criabot::document_create($bot_name, $nodes, $file_name, $file_type);
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


    /**
     * Delete file from indexing server and local database
     * @return bool
     */
    public function delete_record(): bool
    {
        global $DB;
        $context = \context_system::instance();
        // Delete on criabot server
        $result = criabot::document_delete($this->get_bot_name(), $this->get_name());
        if ($result->status == '200') {
            // Delete area on moodle file system
            $fs = get_file_storage();
            $file = $fs->get_file(
                $context->id,
                'local_cria',
                'content',
                $this->get_intent_id(),
                '/',
                $this->get_name()
            );
            $file->delete();
            // Delete on local database
            $DB->delete_records($this->table, array('id' => $this->get_id()));
            return true;
        } else {
            $DB->delete_records($this->table, array('id' => $this->get_id()));
            return false;
        }
    }

    public function update_record($data): int
    {
        global $DB;

        $context = \context_system::instance();

        $file_record = $DB->get_record('local_cria_files', ['id' => $data->id]);
        $file_name = $file_record->name;
        // Set the file path with filename
        $file_path = $data->path . '/' . $file_name;
// save the file
        file_put_contents($file_path, $data->file_content);
// Save file to moodle file system
        $fs = get_file_storage();
        $file_info = [
            'contextid' => $context->id,
            'component' => 'local_cria',
            'filearea' => 'content',
            'itemid' => $data->intent_id,
            'filepath' => '/',
            'filename' => $file_name
        ];
        // First delete the existing file
        $file = $fs->get_file(
            $context->id,
            'local_cria',
            'content',
            $data->intent_id,
            '/',
            $file_name
        );
        if ($file) {
            $file->delete();
        }
        $fs->create_file_from_pathname($file_info, $file_path);
// Get newly created file
        $file = $fs->get_file(
            $context->id,
            'local_cria',
            'content',
            $data->intent_id,
            '/',
            $file_name
        );

        $data->file_type = $this->get_file_type_from_mime_type($file->get_mimetype());
        // if file type is html, convert to txt
        if ($data->file_type == 'html') {
            $file_content = strip_tags($this->delete_all_between('<script', '</script>', $data->file_content));

        }
// Copy to temp area
        $file->copy_content_to($file_path);

        // Update file on indexing server
        $upload = $this->upload_files_to_indexing_server($this->get_bot_name(), $file_path, $file_name, true);
// Delete the file from the server
        unlink($file_path);
        if ($upload->status != 200) {
            \core\notification::error('Error uploading file to indexing server: ' . $upload->message);
        }
        unset($data->path);
        unset($data->file_content);
        return parent::update_record($data); // TODO: Change the autogenerated stub
    }

    /**
     * Delete all between tags
     * @param $beginning
     * @param $end
     * @param $string
     * @return mixed
     */
    public function delete_all_between($beginning, $end, $string)
    {
        $beginningPos = strpos($string, $beginning);
        $endPos = strpos($string, $end);

        if ($beginningPos === false || $endPos === false) {
            return $string;
        }

        $textToDelete = substr($string, $beginningPos, ($endPos + strlen($end)) - $beginningPos);
        return delete_all_between($beginning, $end, str_replace($textToDelete, '', $string));
        // Recursion to ensure all occurrences are replaced
    }

    /**
     * Convert pdf to docx
     * @param $pdf_file_path
     * @param $pdf_file_name
     * @return string
     */
    public function convert_file_to_docx($file_path, $file_name, $file_type = 'pdf')
    {
        global $CFG;
        require_once($CFG->dirroot . '/local/cria/classes/convertapi/lib/ConvertApi/autoload.php');
        $file_path = rtrim($file_path, '/');
        $config = get_config('local_cria');
        \ConvertApi\ConvertApi::setApiSecret($config->convertapi_api_key);
        $result = \ConvertApi\ConvertApi::convert('docx', [
            'File' => $file_path . '/' . $file_name],
            $file_type);
        $result->saveFiles($file_path);
    }

    public function handle_file_conversion($file, $path, $file_name, $content_data, $config) {
        $converted_file_name = '';
        $file_was_converted = false;
        $file->copy_content_to($path . '/' . $file_name);

        if ($config->convertapi_api_key == '') {
            $content_data['name'] = $file_name;
            $converted_file_name = $file_name;
        } else {
            // Convert file to docx if file is a pdf, html, or doc
            // Otherwise leave as is
            switch ($content_data['file_type']) {
                case 'pdf':
                case 'html':
                case 'doc':
                case 'rtf':
                    $converted_file = $FILE->convert_file_to_docx($path, $file_name, $content_data['file_type']);
                    $converted_file_name = str_replace('.' . $content_data['file_type'], '.docx', $file_name);
                    $content_data['file_type'] = 'docx';
                    $content_data['name'] = $converted_file_name;
                    $file_was_converted = true;
                    break;
                default:
                    $content_data['name'] = $file_name;
                    if ($content_data['file_type'] == 'docx') {
                        $file->copy_content_to($path . '/' . $file_name);
                    }
                    $converted_file = $path . '/' . $file_name;
                    $converted_file_name = $file_name;
                    break;
            }
        }
        return [$converted_file_name, $file_was_converted];
    }

}