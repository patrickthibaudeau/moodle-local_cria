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
 * *************************************************************************
 * *                           YULearn ELMS                               **
 * *************************************************************************
 * @package     local                                                     **
 * @subpackage  yulearn                                                   **
 * @name        YULearn ELMS                                              **
 * @copyright   UIT - Innovation lab & EAAS                               **
 * @link                                                                  **
 * @author      Patrick Thibaudeau                                        **
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later  **
 * *************************************************************************
 * ************************************************************************ */

namespace local_cria\output;

use local_cria\keywords;
use local_cria\entity;

class bot_keywords implements \renderable, \templatable
{
    /**
     * @var int
     */
    private $entity_id;

    /**
     * @var int
     */
    private $bot_id;

    /**
     * @var int
     */
    private $active_intent_id;

    public function __construct($entity_id, $bot_id)
    {
        $this->entity_id = $entity_id;
        $this->bot_id = $bot_id;
    }


    /**
     *
     * @param \renderer_base $output
     * @return type
     * @global \moodle_database $DB
     * @global type $USER
     * @global type $CFG
     */
    public function export_for_template(\renderer_base $output)
    {
        global $USER, $CFG, $DB;
        $KEYWORDS = new keywords($this->entity_id);

        $data = [
            'bot_id' => $this->bot_id,
            'entity_id' => $this->entity_id,
            'delete_message' => get_string('delete_keyword_help', 'local_cria'),
            'keywords_page' => true
        ];

        return $data;
    }

}
