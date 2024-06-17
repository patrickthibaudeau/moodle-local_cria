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

// CHECK And PREPARE DATA
global $CFG, $OUTPUT, $SESSION, $PAGE, $DB, $COURSE, $USER;

require_login(1, false);

$bot_id = required_param('bot_id', PARAM_INT);

$context = context_system::instance();

$BOT = new \local_cria\bot($bot_id);

\local_cria\base::page(
    new moodle_url('/local/cria/content.php', ['id' => $bot_id]),
    get_string('content', 'local_cria'),
    get_string('content_for', 'local_cria') . ' ' . $BOT->get_name(),
    $context
);

$PAGE->requires->css('/local/cria/css/content.css');

//$PAGE->requires->js_call_amd('local_cria/content', 'init');
$PAGE->requires->js('/local/cria/js/datatable_documents.js');
$PAGE->requires->js('/local/cria/js/datatable_questions.js');


// Add secondary navigation
include_once('bot_secondaray_nav.php');
//**************** ******
//*** DISPLAY HEADER ***
//**********************
echo $OUTPUT->header();

$output = $PAGE->get_renderer('local_cria');
$content = new \local_cria\output\content($bot_id);
echo $output->render_content($content);
//**********************
//*** DISPLAY FOOTER ***
//**********************
echo $OUTPUT->footer();