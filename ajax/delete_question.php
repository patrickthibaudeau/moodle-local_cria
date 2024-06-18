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


use local_cria\question;

require_once('../../../config.php');
$context = context_system::instance();
require_login(1, false);

$question_id = optional_param('id', 0,PARAM_INT);
$question_ids = optional_param_array('questions', array(), PARAM_RAW);

if ($question_id != 0) {
//Create entity object
    $QUESTION = new question($question_id);

//Delete entity
    $status = $QUESTION->delete_record();

    if ($status) {
        $status = array('status' => 200, 'message' => 'Question deleted successfully');
    } else {
        $status = array('status' => 404, 'message' => 'Error deleting question');
    }
} else {
    // loop through question_ids and delete each question
    foreach ($question_ids as $id) {
        //Create entity object
        $QUESTION = new question($id);
        //Delete entity
        $status = $QUESTION->delete_record();
        unset($QUESTION);
        if ($status) {
            $status = array('status' => 200, 'message' => 'Question deleted successfully');
        } else {
            $status = array('status' => 404, 'message' => 'Error deleting question');
            break;
        }
    }
}

echo json_encode($status);