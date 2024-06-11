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
use local_cria\entity;
// CHECK And PREPARE DATA
global $CFG, $OUTPUT, $SESSION, $PAGE, $DB, $COURSE, $USER;

require_login(1, false);
$context = context_system::instance();

$entity_id = required_param('id', PARAM_INT);

$ENTITY = new entity($entity_id);
$BOT = new bot($ENTITY->get_bot_id());

\local_cria\base::page(
    new moodle_url('/local/cria/bot_config.php'),
    $BOT->get_name() . ' - ' . $ENTITY->get_name(),
    $BOT->get_name() . ' - ' . $ENTITY->get_name(),
    $context);

$PAGE->requires->js('/local/cria/js/datatable_keywords.js', true);
//**************** ******
//*** DISPLAY HEADER ***
//**********************
echo $OUTPUT->header();

$output = $PAGE->get_renderer('local_cria');
$keywords = new \local_cria\output\bot_keywords($entity_id, $ENTITY->get_bot_id());
echo $output->render_bot_keywords($keywords);
//**********************
//*** DISPLAY FOOTER ***
//**********************
echo $OUTPUT->footer();