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

class bot_permissions implements \renderable, \templatable
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

        $context = \context_system::instance();
        $BOT_ROLE = new bot_role();
        // If bot_admin role does not exist, create it
        if (!$BOT_ROLE->bot_admin_role_exists($this->bot_id)) {
            $BOT_ROLE->create_default_roles($this->bot_id);
        }

        $BOT_ROLES = new bot_roles($this->bot_id);

        $data = [
            'bot_id' => $this->bot_id,
            'roles' => $BOT_ROLES->get_records_for_template()
        ];

        return $data;
    }

}
