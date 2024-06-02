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


/**
 * Build secondary menu
 * NOT YET USED BUT WILL BE USED IN THE FUTURE
 */
global $PAGE;

$bot_id = required_param('bot_id', PARAM_INT);
$context = context_system::instance();
//$PAGE->requires->css(new moodle_url('/local/yulearn/css/admin_navigation.css'));
$PAGE->secondarynav->add(
    get_string('content', 'local_cria'),
    new moodle_url('/local/cria/content.php?bot_id=' . $bot_id)
);
$PAGE->secondarynav->add(
    get_string('entities', 'local_cria'),
    new moodle_url('/local/cria/bot_entities.php?bot_id=' . $bot_id)
);
$PAGE->secondarynav->add(
    get_string('edit_bot', 'local_cria'),
    new moodle_url('/local/cria/edit_bot.php?bot_id=' . $bot_id)
);
$PAGE->secondarynav->add(
    get_string('test_bot', 'local_cria'),
    new moodle_url('/local/cria/test_bot.php?bot_id=' . $bot_id)
);
$PAGE->secondarynav->add(
    get_string('logs', 'local_cria'),
    new moodle_url('/local/cria/bot_logs.php?bot_id=' . $bot_id)
);
