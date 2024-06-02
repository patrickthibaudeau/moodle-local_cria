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

$path = required_param('path', PARAM_TEXT);
$file_name = required_param('file', PARAM_TEXT);

serve_file($path, $file_name);

function serve_file($filepath, $new_filename=null) {
    $filename = basename($filepath);
    if (!$new_filename) {
        $new_filename = $filename;
    }
    $mime_type = mime_content_type($filepath);
    header('Content-type: '.$mime_type);
    header('Content-Disposition: attachment; filename="'.$new_filename.'"');
    readfile($filepath);
}

