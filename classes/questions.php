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

class questions
{

    /**
     * @var int
     */
    private $intent_id;
    /**
     *
     *@global \moodle_database $DB
     */
    public function __construct($intent_id = 0)
    {
        global $DB, $USER;
        $this->intent_id = $intent_id;
    }

    /**
     * Get records
     */
    public function get_records(): array {
        global $DB;
        return $DB->get_records('local_cria_question', array('intent_id' => $this->intent_id), 'timemodified DESC');
    }

    /**
     * @param $intent_id
     * @return array|false
     * @throws \dml_exception
     */
    public function get_questions()
    {
        global $DB;
        if ($questions = $DB->get_records('local_cria_question', ['intent_id' => $this->intent_id], 'timemodified DESC')) {
            foreach ($questions as $question) {
                // Get unindexed (unpublished) examples
                $question_examples = $DB->get_records(
                    'local_cria_question_example',
                    [
                        'questionid' => $question->id,
                        'indexed' => 0
                    ],
                    'id');
                if ($question_examples) {
                    $question->published = false;
                }
            }
            return array_values($questions);
        }
        return [];
    }

    /**
     * Delete all questions
     * @return int
     * @throws \dml_exception
     */
    public function delete_all(): int
    {
        global $DB;
        $INTENT = new intent($this->intent_id);
        // Get all questions
        $questions = $DB->get_records('local_cria_question', ['intent_id' => $this->intent_id], null, 'id');
        // Loop through all questions and delete examples
        foreach ($questions as $question) {
           if (!$delete = $DB->delete_records('local_cria_question_example', ['questionid' => $question->id]))
           {
               return 404;
           }
        }
        // Get all questions stored on creabot server
        $criabot_questions = criabot::question_list($INTENT->get_bot_name());
        // Loop through all questions and delete them from creabot server
        foreach ($criabot_questions->document_names as $question) {
            criabot::question_delete($INTENT->get_bot_name(), $question);
        }
        if (!$delete_questions = $DB->delete_records('local_cria_question', ['intent_id' => $this->intent_id])) {
            return 404;
        }

        return 200;
    }

    /**
     * Export questions
     * @return int
     * @throws \dml_exception
     */
    public function export_data() {
        global $DB;
        $questions = $DB->get_records('local_cria_question', ['intent_id' => $this->intent_id], 'value');
        $data = [];
        $i = 0;
        foreach ($questions as $question) {
            $examples = [$question->value];
            // Get example questions
            $question_examples = $DB->get_records('local_cria_question_example', ['questionid' => $question->id], 'id');
            foreach ($question_examples as $example) {
                $examples[] = $example->value;
            }
            // Set keywords
            if ($question->keywords != null) {
                $keywords = json_decode($question->keywords);
            } else {
                $keywords = [];
            }
            $keywords = implode('|', $keywords);
            // Must set a name
            if (empty($question->name)) {
                $name = $i;
            } else {
                $name = $question->name;
            }
            $params = new \stdClass();
            $params->name = $name;
            $params->examples = $examples;
            $params->answer = $question->answer;
            $params->keywords = $keywords;
            $params->lang = $question->lang;
            $data[$i] = $params;
            $i++;
        }
        return $data;
    }

    /**
     * Export question in json format
     * @return string
     * @throws \dml_exception
     */
    public function export_json() {
        $INTENT = new intent($this->intent_id);
        $json = json_encode($this->export_data(), JSON_PRETTY_PRINT);
        $file_name = $INTENT->get_name() . '_' . date('m_d_Y', time())  .'.json';
        header('Content-disposition: attachment; filename="'. $file_name . '"');
        header('Content-type: application/json');
        echo $json;
    }

    /**
     * Export questions in excel format
     * @return string
     * @throws \dml_exception
     */
    public function export_excel() {
        $INTENT = new intent($this->intent_id);
        $questions = $this->export_data();
        $file_name = $INTENT->get_name() . '_' . date('m_d_Y', time())  .'.xlsx';
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        // Set fields
        $fields = ['name', 'examples', 'answer', 'keywords', 'lang'];
        // Set row one columns
        $sheet->fromArray($fields, NULL, 'A1');
        // Start at row 2
        $row = 2;
        // Loop through all questions
        foreach ($questions as $question) {
            // Set parent
            $sheet->setCellValue('A' . $row, $question->name);
            // Set answer
            $sheet->setCellValue('C' . $row, $question->answer);
            // Set keywords
            $sheet->setCellValue('D' . $row, $question->keywords);
            // Set lang
            $sheet->setCellValue('E' . $row, $question->lang);
            // Set first example question
            $sheet->setCellValue('B' . $row, $question->examples[0]);
            // Loop through all example questions and add to the excel sheet.
            $row++;
            foreach ($question->examples as $key => $example) {
                if ($key == 0) {
                    continue;
                }
                $sheet->setCellValue('A' . $row, $question->name);
                $sheet->setCellValue('B' . $row, $example);
                $row++;
            }
        }

        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="'. urlencode($file_name).'"');
        $writer->save('php://output');
    }
}