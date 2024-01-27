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

use local_cria\files;
use local_cria\intent;

class intent_questions implements \renderable, \templatable
{
    /**
     * @var int
     */
    private $intent_id;

    public function __construct($intent_id)
    {
        $this->intent_id = $intent_id;
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

        $INTENT = new intent($this->intent_id);

        $data = [
            'intent_id' => $this->intent_id,
            'bot_id' => $INTENT->get_bot_id(),
            'intent_name' => $INTENT->get_name(),
            'questions' => $INTENT->get_questions(),
        ];

        return $data;
    }

}
