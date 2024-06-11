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


require_once('../../config.php');

use local_cria\bot;

// CHECK And PREPARE DATA
global $CFG, $OUTPUT, $SESSION, $PAGE, $DB, $COURSE, $USER;

require_login(1, false);
$context = context_system::instance();

$bot_role_id = optional_param('id',0, PARAM_INT);
$bot_id = required_param('botid', PARAM_INT);
$data = (object)$_POST; // Form data

if (!has_capability('local/cria:view_bots', $context)) {
    throw new moodle_exception('nopermissions', 'error', '', 'You do not have permissions to access this page');
}


\local_cria\base::page(
    new moodle_url('/local/cria/edit_bot_capabilities.php', ['id' => $bot_role_id]),
    get_string('permissions', 'local_cria') ,
    get_string('permissions', 'local_cria') ,
    $context);

$PAGE->requires->js_call_amd('local_cria/bot_config', 'init');
//**************** ******
//*** DISPLAY HEADER ***
//**********************
echo $OUTPUT->header();

if (isset($data->save)) {
    $BOT_ROLE = new \local_cria\bot_role($data->id);
    if ($data->id) {
        $BOT_ROLE->update_record($data);
    } else {
        $BOT_ROLE->insert_record($data);
    }
    redirect(new moodle_url('/local/cria/bot_permissions.php', ['bot_id' => $data->botid]));
}

$output = $PAGE->get_renderer('local_cria');
$dashboard = new \local_cria\output\edit_bot_role($bot_id, $bot_role_id);
echo $output->render_edit_bot_role($dashboard);
//**********************
//*** DISPLAY FOOTER ***
//**********************
echo $OUTPUT->footer();