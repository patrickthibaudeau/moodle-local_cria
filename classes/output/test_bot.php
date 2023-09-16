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

require_once($CFG->dirroot . '/local/cria/classes/gpttokenizer/Gpt3Tokenizer.php');
require_once($CFG->dirroot . '/local/cria/classes/gpttokenizer/Gpt3TokenizerConfig.php');
require_once($CFG->dirroot . '/local/cria/classes/gpttokenizer/Vocab.php');
require_once($CFG->dirroot . '/local/cria/classes/gpttokenizer/Merges.php');

use local_cria\bot;
use local_cria\files;
use local_cria\Gpt3TokenizerConfig;
use local_cria\Gpt3Tokenizer;
use local_cria\cria;

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
            $session = cria::start_chat($this->bot_id);
            $chat_id = $session->chat_id;
        } else {
            $cache = \cache::make('local_cria', 'cria_system_messages');
            $system_message = $cache->set($BOT->get_bot_type() . '_' . sesskey(), $BOT->get_bot_type_system_message()  . ' ' . $BOT->get_bot_system_message());
        }

        $data = [
            'bot_id' => $this->bot_id,
            'name' => $BOT->get_name(),
            'use_bot_server' => $BOT->use_bot_server(),
            'chat_id' => $chat_id,
            'user_prompt' => $BOT->get_user_prompt(),
        ];
        return $data;
    }

}
