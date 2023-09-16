<?php
require_once('../../../config.php');

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

