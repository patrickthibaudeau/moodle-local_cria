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

use local_cria\providers;
use local_cria\models;

class bot_models implements \renderable, \templatable
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

        $MODELS = new \local_cria\models();

        $bot_models = array_values($MODELS->get_formated_records());

        $PROVIDERS = new providers();

        $data = [
            'bot_models' => $bot_models,
            'providers' => $PROVIDERS->get_formated_records()
        ];

        return $data;
    }

}
