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
        $INTENTS = new intents($this->bot_id);
        $intents = array_values($INTENTS->get_records_with_related_data($this->active_intent_id));

        $data = [
            'bot_id' => $this->bot_id,
            'intents' => $intents,
            'use_fine_tuning' => $BOT->get_fine_tuning(),
        ];

        return $data;
    }

}
