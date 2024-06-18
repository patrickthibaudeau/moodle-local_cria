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

global $CFG, $DB, $USER;

$context = context_system::instance();
require_login(1, false);

// Get values from checkbox select_option
$ids = optional_param_array('questions', [], PARAM_INT);

foreach ($ids as $id) {
 $QUESTION = new question($id);
 $status = $QUESTION->publish();
 if ($status->status == 404) {
     echo json_encode($status);
     break;
 }
 unset($QUESTION);
}
// Echo the last status
echo json_encode($status);


