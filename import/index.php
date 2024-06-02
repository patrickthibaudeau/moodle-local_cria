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



require_once("../../../config.php");

require_once($CFG->dirroot . "/local/cria/classes/forms/question_import_form.php");


use local_cria\base;
use local_cria\intent;
use local_cria\import;


global $CFG, $OUTPUT, $USER, $PAGE, $DB, $SITE;

$context = CONTEXT_SYSTEM::instance();

require_login(1, false);
// Get bot id
$intent_id = required_param('intent_id', PARAM_INT);
$err = optional_param('err', '', PARAM_TEXT);

$INTENT = new intent($intent_id);


$formdata = new stdClass();
// Set bot id in formdata
$formdata->intent_id = $intent_id;
$formdata->bot_id = $INTENT->get_bot_id();
// Create form
$mform = new \local_cria\question_import_form(null, array('formdata' => $formdata));
if ($mform->is_cancelled()) {
    //Handle form cancel operation, if cancel button is present on form
    redirect($CFG->wwwroot . '/local/cria/content.php?bot_id=' . $formdata->bot_id . '&intent_id=' . $formdata->intent_id);
} else if ($data = $mform->get_data()) {
    // Check to see if cria directory exists
    $path = $CFG->dataroot . '/temp/cria';
    if (!is_dir($path)) {
        mkdir($path);
    }
    // Check to see if directory for this intent exists
    $path = $CFG->dataroot . '/temp/cria/' . $data->intent_id;
    if (!is_dir($path)) {
        mkdir($path);
    }
    // Save file to directory
    $file_name = $mform->get_new_filename('importedFile');
    $full_path = $path . '/' . $file_name;
    $success = $mform->save_file('importedFile', $full_path, true);

    $IMPORT = new import($full_path);
    if ($IMPORT->get_file_type() == 'XLSX') {
        $columns = $IMPORT->get_columns();
        $rows = $IMPORT->get_rows();
        $IMPORT->questions_excel($data->intent_id, $columns, $rows);
    } else {
        $json = file_get_contents($full_path);
        $file_data = json_decode($json);
        $IMPORT->questions_json($data->intent_id, $file_data);
    }
    unlink($full_path);
    // Redirect to content page
    redirect($CFG->wwwroot . '/local/cria/content.php?bot_id=' . $data->bot_id . '&intent_id=' . $data->intent_id);
} else {
    // Show form
    $mform->set_data($mform);
}


base::page(
    new moodle_url('/local/cria/import/index.php', ['intent_id' => $intent_id]),
    get_string('import_questions', 'local_cria'),
    '',
    $context,
    'standard'
);

echo $OUTPUT->header();
//**********************
//*** DISPLAY HEADER ***
//
if ($err != '') {
    \core\notification::error(get_string('column_name_must_exist', 'local_cria', [$err]));
}
$mform->display();

//**********************
//*** DISPLAY FOOTER ***
//**********************
echo $OUTPUT->footer();
?>