<?php
/**
 * This file is part of Cria.
 * Cria is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.
 * Cria is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License along with Cria. If not, see <https://www.gnu.org/licenses/>.
 *
 * @package    local_cria
 * @author     Patrick Thibaudeau
 * @copyright  2024 onwards York University (https://yorku.ca)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_cria\task;

use local_cria\files;

class update_url_content extends \core\task\scheduled_task
{
    public function execute()
    {
        global $DB, $CFG;

        // Get all intents
        $intents_sql = "SELECT * FROM {local_cria_intents} WHERE published = 1";
        $intents = $DB->get_records_sql($intents_sql);

        // Loop through intents and get content with a url
        foreach ($intents as $intent) {
            // Get files for intent where content is not empty
            $files_sql = "SELECT * FROM {local_cria_files} WHERE intent_id = ? AND content != ''";
            $files = $DB->get_records_sql($files_sql, [$intent->id]);
            // Loop through file and set URL array
            $urls = [];
            if ($files) {
                foreach ($files as $file) {
                    // Check to make sure the content is a URL
                    if (filter_var(trim($file->content), FILTER_VALIDATE_URL)) {
                        $urls[] = $file->content;
                    }
                }
                mtrace('URLs: ' . print_r($urls, true));
                // publish urls using the files class
                $FILES = new files($intent->id);
                $FILES->publish_urls($urls);
                // clear class $FILES
                unset($FILES);
            }
        }
    }

    public function get_name(): string
    {
        return get_string('update_url_content', 'local_cria');
    }

    public function get_run_if_component_disabled()
    {
        return true;
    }
}

