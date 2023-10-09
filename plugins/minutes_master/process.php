<?php
require_once('../../../../config.php');

use local_cria\minutes_master;

$data = (object)$_POST;
$minutes = $data->minutes;
$project_name = $data->project_name;
$date = $data->date;
$time = $data->time;
$location = $data->location;
$file_path = '';

// Convert br to new line
$minutes = str_replace('<br />', "\n", $minutes);
$minutes = str_replace('<br/>', "\n", $minutes);
$minutes = str_replace('<br>', "\n", $minutes);
// Remove any other html tags
$minutes = strip_tags($minutes);

// Create word file from template and download it
echo minutes_master::create_document($minutes, $project_name, $date, $time, $location, $file_path);

