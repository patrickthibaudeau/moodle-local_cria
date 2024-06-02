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

use local_cria\bot;
use local_cria\files;
use local_cria\intents;

class content implements \renderable, \templatable
{
    /**
     * @var int
     */
    private $bot_id;

    /**
     * @var int
     */
    private $active_intent_id;

    public function __construct($bot_id, $activer_intent_id = 0)
    {
        $this->bot_id = $bot_id;
        $this->active_intent_id = $activer_intent_id;
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
        $BOT = new bot($this->bot_id);
        if ($this->active_intent_id == 0) {
            $this->active_intent_id = $BOT->get_default_intent_id();
        }

        $context = \context_system::instance();

        $INTENTS = new intents($this->bot_id);
        $intents = array_values($INTENTS->get_records_with_related_data($this->active_intent_id));

        $data = [
            'bot_id' => $this->bot_id,
            'context_id' => $context->id,
            'intents' => $intents,
            'use_fine_tuning' => $BOT->get_fine_tuning(),
            'content_page' => true,
            'return_url' => 'content'
        ];

        return $data;
    }

}
