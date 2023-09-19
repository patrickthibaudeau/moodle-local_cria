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
class content implements \renderable, \templatable
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

        $FILES = new files($this->bot_id);

        $files = $FILES->get_records();
        $files = array_values($files);
        $word_count = str_word_count($FILES->concatenate_content());
        $data = [
            'bot_id' => $this->bot_id,
            'bot_name' => $FILES->get_bot_name(),
            'files' => $files,
            'word_count' => $word_count,
        ];
        return $data;
    }

}
