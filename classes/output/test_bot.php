<?php

/**
* This file is part of Crai.
* Cria is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.
* Crai is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
* You should have received a copy of the GNU General Public License along with Crai. If not, see <https://www.gnu.org/licenses/>.
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
use local_cria\Gpt3TokenizerConfig;
use local_cria\Gpt3Tokenizer;
use local_cria\criabot;

class test_bot implements \renderable, \templatable
{

    /**
     * @var int
     */
    private $bot_id;

    public function __construct($bot_id)
    {
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

        $BOT = new bot($this->bot_id);

        $chat_id = 0;
        if ($BOT->use_bot_server()) {
            $session = criabot::chat_start();
            $chat_id = $session->chat_id;
        }
        $debug = false;
        if ($CFG->debug != 0) {
            $debug = true;
        }
        $data = [
            'bot_id' => $this->bot_id,
            'name' => $BOT->get_name(),
            'use_bot_server' => $BOT->use_bot_server(),
            'chat_id' => $chat_id,
            'user_prompt' => $BOT->get_user_prompt(),
            'requires_content_prompt' => $BOT->get_requires_content_prompt(),
            'requires_user_prompt' => $BOT->get_requires_user_prompt(),
            'has_auto_test' => $BOT->get_has_auto_test_questions(),
            'test_bot_page' => true,
            'return_url' => 'test_bot',
            'debug' => $debug,
        ];

        return $data;
    }

}
