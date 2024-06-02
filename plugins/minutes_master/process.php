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


require_once('../../../../config.php');

use local_cria\minutes_master;

$data = (object)$_POST;

$minutes_text = html_entity_decode($data->minutes);
$minutes = '';
$project_name = $data->project_name;
$date = $data->date;
$time = $data->time;
$location = $data->location;
$file_path = '';
$minutes_text = str_replace('@@@@', "@@\n\n@@" , $minutes_text);

// Split data based on @@topic_result@@ and @@/topic_result@@
$pattern = '/@@topic_result@@(.*?)@@\/topic_result@@/s';
preg_match_all($pattern, $minutes_text, $matches);

for ($i = 0; $i < count($matches[1]); $i++) {
    $result[$i] = $matches[1][$i];
    $result[$i] = str_replace('@@topic_result@@', '', $result[$i]);
    $result[$i] = str_replace('@@/topic_result@@', '', $result[$i]);
    $result[$i] = str_replace('<br />', "\n", $result[$i]);
    // Split data into array based on new line
    $result_array = explode("\n", $result[$i]);
    // If an array element contains a ], remove the comma from the previous element (-2)
    foreach ($result_array as $key => $value) {
        if (strstr($value, ']')) {
            $result_array[$key - 2] = rtrim($result_array[$key - 2], ',');
        }
        // Remove action items if therer are no action items
        if (strpos($value, '"action_items": ' . "\n") !== false) {
            $result_array[$key - 2] = rtrim($result_array[$key - 2], ',');
            print_object('Need to unset');
            unset($key);
        }
    }
    print_object($result_array);
    die;
    // Reset the original array $result[$i] with the new array $result_array
    $result[$i] = implode("\n",$result_array);

    // convert result[$i] json to array
//    $result[$i] = json_decode($result[$i], true);
}
die;
// Create minutes by iterating through the result array
//$x will be used for the topic list
$x = 1;
for ($i = 0; $i < count($result); $i++) {
    $minutes .= "\n $x. " . $result[$i][0]['topic']. "\n";
    foreach ($result[$i][0]['topic_notes'] as $key => $value) {
        $minutes .= "\t - " . $value['note'] . "\n";
    }
    $minutes .= "\n";
    $x++;
}

// Repeat the process for the action items
$x = 1;
$minutes .= "\n\nAction Items\n";
for ($i = 0; $i < count($result); $i++) {
    foreach ($result[$i][0]['action_items'] as $key => $value) {
        $minutes .= "\t - Assigned to: " . $value['assigned_to'] . "\n";
        $minutes .= "\t   Description: " . $value['description'] . "\n";
        $minutes .= "\t   Due date: " . $value['date_due'] . "\n\n";
    }
    $minutes .= "\n";
    $x++;
}

// Convert br to new line
//$minutes = str_replace('<br />', "\n", $minutes);
//$minutes = str_replace('<br/>', "\n", $minutes);
//$minutes = str_replace('<br>', "\n", $minutes);
//// Remove any other html tags
//$minutes = strip_tags($minutes);

// Create word file from template and download it
echo minutes_master::create_document($minutes, $project_name, $date, $time, $location, $file_path);

