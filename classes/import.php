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



namespace local_cria;

require_once($CFG->libdir . '/phpspreadsheet/vendor/autoload.php');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class import
{
    /**
     * @var false|\PhpOffice\PhpSpreadsheet\Worksheet\Worksheet
     */
    private $worksheet;

    /**
     * @var string
     */
    private $file_type;

    /**
     * @param $file string  Path to file
     */
    public function __construct($file = '')
    {
        if ($file) {
            // Make sure we have an .xlsx file
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $file_info = finfo_file($finfo, $file);
            $this->file_type = null;

            if ($file_info == 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet') {
                $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
                $spread_sheet = $reader->load($file);
                $this->worksheet = $spread_sheet->getActiveSheet();
                $this->file_type = 'XLSX';
            } else if ($file_info == 'application/json') {
                $this->worksheet = false;
                $this->file_type = 'JSON';
            } else {
                notification::error('You must upload an xlsx file');
                $this->worksheet = false;
                $this->file_type = null;
            }
        }
    }

    /**
     * Return file type
     * @return string
     */
    public function get_file_type(): string
    {
        return $this->file_type;
    }

    /**
     * Returns an array of all columns in the first row of the work sheet
     * @return array
     */
    public function get_columns()
    {
        $worksheet = $this->worksheet;
        $worksheet_array = $worksheet->toArray();
//        print_object($worksheet_array);
        $columns = [];
        for ($i = 0; $i < count($worksheet_array[0]); $i++) {
            if ($worksheet_array[0][$i]) {
                $columns[$i] = $worksheet_array[0][$i];
            }
        }
        return $columns;
    }

    /**
     * Returns all rows as an array
     * @return array
     */
    public function get_rows()
    {
        raise_memory_limit(MEMORY_UNLIMITED);
        $worksheet = $this->worksheet;
        $worksheet_array = $worksheet->toArray();
        $number_of_rows = count($worksheet_array);
        $columns = $this->get_columns();
        $data = [];
        $rows = [];
        // Start at 1 because 0 is the first row
        for ($i = 1; $i <= $number_of_rows; $i++) {

            foreach ($columns as $key => $column) {
                if (isset($worksheet_array[$i][$key])) {
                    $rows[$i][$key] = $worksheet_array[$i][$key];
                } else {
                    $rows[$i][$key] = '';
                }
            }

        }
        raise_memory_limit(MEMORY_STANDARD);
        return $rows;
    }

    /**
     * Returns array for colum names
     * @return array
     */
    public function clean_column_names()
    {
        $columns = $this->get_columns();
        $column_names = [];
        foreach ($columns as $key => $column) {
            $column_names[$key] = new \stdClass();
            $column_names[$key]->fullname = $column;
            // Clean the column name
            $clean_column = preg_replace('/[^\w\s]+/', '', $column);;
            $clean_column = str_replace(" ", '_', $clean_column);
            $clean_column = strtolower($clean_column);
            $column_names[$key]->shortname = $clean_column;

        }

        return $column_names;
    }

    /**
     * @param $columns array
     * @param $rows array
     * @return void
     */
    public function questions_excel($intent_id, $columns, $rows)
    {
        global $CFG, $DB, $USER;
        // Make sure the columns exist
        if (!in_array('name', $columns)) {
            redirect($CFG->wwwroot . '/local/cria/import/index.php?intent_id=' . $intent_id . '&err=name');
        }
        if (!in_array('examples', $columns)) {
            redirect($CFG->wwwroot . '/local/cria/import/index.php?intent_id=' . $intent_id . '&err=examples');
        }
        if (!in_array('answer', $columns)) {
            redirect($CFG->wwwroot . '/local/cria/import/index.php?intent_id=' . $intent_id . '&err=answer');
        }

        // set intent
        $INTENT = new intent($intent_id);

        // Set the proper column key
        $keywords = false;
        $lang = false;
        // Set the proper key value for the columns
        foreach ($columns as $key => $column) {
            switch (trim($column)) {
                case 'name':
                    $name = $key;
                    break;
                case 'examples':
                    $example = $key;
                    break;
                case 'answer':
                    $answer = $key;
                    break;
                case 'keywords':
                    $keywords = $key;
                    break;
                case 'lang':
                    $lang = $key;
                    break;
                case 'generate_examples':
                    $generate_examples = $key;
                    break;
            }
        }

        $current_name = '';
        for ($i = 1; $i < count($rows); $i++) {
            if (trim($rows[$i][$name]) != $current_name) {
                $x = 0; // Used to avoid importing examples when no answer is available
                // Do not create a quesiton if answer is empty
                if (empty(trim($rows[$i][$answer]))) {
                    continue;
                }
                // Create question
                $params = [
                    'intent_id' => $intent_id,
                    'name' => str_replace('_', ' ', trim($rows[$i][$name])),
                    'value' => trim($rows[$i][$example]),
                    'answer' => trim($rows[$i][$answer]),
                    'timecreated' => time(),
                    'timemodified' => time(),
                    'usermodified' => $USER->id
                ];
                if ($keywords) {
                    $params['keywords'] = trim($rows[$i][$keywords]);
                }
                if ($lang) {
                    $params['lang'] = trim($rows[$i][$lang]);
                }

                $question_id = $DB->insert_record('local_cria_question', $params);
                $DB->set_field('local_cria_question', 'parent_id', $question_id, ['id' => $question_id]);
                // If generate_examples is set to 1, then we will generate examples with LLM
                if (trim($rows[$i][$generate_examples] == 1)) {
                    $INTENT->generate_example_questions($question_id);
                }
                $x++;
            } else {
                // Create example
                if ($x != 0) {
                    $param_example = [
                        'questionid' => $question_id,
                        'value' => trim($rows[$i][$example]),
                        'timecreated' => time(),
                        'timemodified' => time(),
                        'usermodified' => $USER->id
                    ];
                    $DB->insert_record('local_cria_question_example', $param_example);
                }

            }
            $current_name = trim($rows[$i][$name]);
        }

        return true;
    }

    public function questions_json($intent_id, $questions)
    {
        global $DB, $USER;
        // Set the intent
        $INTENT = new intent($intent_id);
        $params = [];
        foreach ($questions as $question) {
            $params[$i]['name'] = $question->name;
            $params[$i]['value'] = $question->examples[0];
            $params[$i]['answer'] = $question->answer;
            $params[$i]['lang'] = $question->lang;
            $params[$i]['intent_id'] = $intent_id;
            $params[$i]['usermodified'] = $USER->id;
            $params[$i]['generate_answer'] = 0;
            $params[$i]['timemodified'] = time();
            $params[$i]['timecreated'] = time();
            $params[$i]['id'] = $DB->insert_record('local_cria_question', $params[$i]);

            // Update record adding the question id to the parent_id
            $params[$i]['parent_id'] = $params[$i]['id'];
            $DB->update_record('local_cria_question', $params[$i]);
            if (count($question->examples) > 1) {
                $this->create_examples($params[$i]['id'], $question->examples);
            } else {
                $INTENT->generate_example_questions($params[$i]['id']);
            }
            $i++;
        }
    }

    /**
     * @param $question_id
     * @param $examples
     * @return void
     * @throws \dml_exception
     */
    private function create_examples($question_id, $examples)
    {
        global $DB;
        $i = 0;
        $params = [];
        foreach ($examples[0]->examples as $example) {
            if ($i > 0) {
                $params[$i]['value'] = $example;
                $params[$i]['questionid'] = $question_id;
                $params[$i]['usermodified'] = 2;
                $params[$i]['timemodified'] = time();
                $params[$i]['timecreated'] = time();
                $DB->insert_record('local_cria_question_example', $params[$i]);
            }
            $i++;
        }
    }
}