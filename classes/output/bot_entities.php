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
use local_cria\entities;

class bot_entities implements \renderable, \templatable
{
    /**
     * @var int
     */
    private $bot_id;

    /**
     * @var int
     */
    private $active_intent_id;

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

        $data = [
            'bot_id' => $this->bot_id,
            'delete_message' => get_string('delete_entity_help', 'local_cria'),
            'entities_page' => true,
            'return_url' => 'bot_entities',
        ];

        return $data;
    }

}
