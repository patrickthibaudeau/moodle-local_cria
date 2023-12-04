<?php
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
use local_cria\cria;
use local_cria\output\type;

class bot_app implements \renderable, \templatable
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
            $session = cria::start_chat($this->bot_id . '-' . $BOT->get_default_intent_id());
            $chat_id = $session->chat_id;
        }

        $data = [
            'bot_id' => $this->bot_id,
            'name' => $BOT->get_name(),
            'use_bot_server' => $BOT->use_bot_server(),
            'chat_id' => $chat_id,
            'user_prompt' => $BOT->get_user_prompt(),
            'requires_content_prompt' => $BOT->get_requires_content_prompt(),
            'requires_user_prompt' => $BOT->get_requires_user_prompt(),
        ];
        return $data;
    }

}
