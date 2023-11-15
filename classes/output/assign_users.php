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

use local_cria\bot_roles;
use local_cria\bot_role;
use local_cria\bot;

class assign_users implements \renderable, \templatable
{

    /**
     * @var int
     */
    private $bot_id;

    public function __construct($role_id, $bot_id)
    {
        $this->role_id = $role_id;
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

        $context = \context_system::instance();
        $BOT_ROLE = new bot_role($this->role_id);
        $BOT = new bot($this->bot_id);

        $data = [
            'role_id' => $this->role_id,
            'bot_id' => $this->bot_id,
            'role_name' => $BOT_ROLE->get_name(),
            'bot_name' => $BOT->get_name(),
        ];

        return $data;
    }

}
