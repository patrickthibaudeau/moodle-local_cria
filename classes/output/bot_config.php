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

use local_cria\bots;

class bot_config implements \renderable, \templatable
{


    public function __construct()
    {

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
        $config = get_config('local_cria');

        $BOTS = new bots();

        $bots = $BOTS->get_records();
        $bots = array_values($bots);

        $data = [
            'bots' => $bots,
            'can_edit' => has_capability('local/cria:edit_bots', $context),
            'can_delete' => has_capability('local/cria:delete_bots', $context),
            'can_test' => has_capability('local/cria:test_bots', $context),
            'can_view_bot_types' => has_capability('local/cria:view_bot_types', $context),
            'can_edit_bot_content' => has_capability('local/cria:edit_bot_content', $context),
            'can_view_bot_logs' => has_capability('local/cria:view_bot_logs', $context),
            'can_view_bot_models' => has_capability('local/cria:view_models', $context),
            'can_share_bots' => has_capability('local/cria:share_bots', $context),
            'can_add_bot_permission' => has_capability('local/cria:bot_permissions', $context),
            "criachat" => $config->embedding_server_url
        ];

        return $data;
    }

}
