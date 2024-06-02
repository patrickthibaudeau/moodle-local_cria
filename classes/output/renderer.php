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



namespace local_cria\output;

/**
 * Description of renderer
 *
 * @author patrick
 */
class renderer extends \plugin_renderer_base {

    /**
     * @param \templatable $role
     * @return bool|string
     * @throws \moodle_exception
     */
    public function render_provider_dashboard(\templatable $provider_dashboard) {
        $data = $provider_dashboard->export_for_template($this);
        return $this->render_from_template('local_cria/provider_dashboard', $data);
    }

    /**
     * Used with root/index.php
     * @param \templatable $dashboard
     * @return type
     */
    public function render_dashboard(\templatable $dashboard) {
        $data = $dashboard->export_for_template($this);
        return $this->render_from_template('local_cria/dashboard', $data);
    }

    /**
     * Used with root/index.php
     * @param \templatable $dashboard
     * @return type
     */
    public function render_bot_config(\templatable $bot_config) {
        $data = $bot_config->export_for_template($this);
        return $this->render_from_template('local_cria/bot_config', $data);
    }

    /**
     * @param \templatable $content
     * @return bool|string
     * @throws \moodle_exception
     */
    public function render_content(\templatable $content) {
        $data = $content->export_for_template($this);
        return $this->render_from_template('local_cria/content', $data);
    }

    /**
     * @param \templatable $bot_types
     * @return bool|string
     * @throws \moodle_exception
     */
    public function render_bot_types(\templatable $bot_types) {
        $data = $bot_types->export_for_template($this);
        return $this->render_from_template('local_cria/bot_types', $data);
    }

    /**
     * @param \templatable $bot_types
     * @return bool|string
     * @throws \moodle_exception
     */
    public function render_test_bot(\templatable $test_bot) {
        $data = $test_bot->export_for_template($this);
        return $this->render_from_template('local_cria/test_bot', $data);
    }

    /**
     * @param \templatable $bot_types
     * @return bool|string
     * @throws \moodle_exception
     */
    public function render_bot_logs(\templatable $logs) {
        $data = $logs->export_for_template($this);
        return $this->render_from_template('local_cria/bot_logs', $data);
    }


    /**
     * @param \templatable $bot_types
     * @return bool|string
     * @throws \moodle_exception
     */
    public function render_bot_models(\templatable $models) {
        $data = $models->export_for_template($this);
        return $this->render_from_template('local_cria/bot_models', $data);
    }

    /**
     * @param \templatable $bot_types
     * @return bool|string
     * @throws \moodle_exception
     */
    public function render_minutes_master(\templatable $message) {
        $data = $message->export_for_template($this);
        return $this->render_from_template('local_cria/minutes_master', $data);
    }

    /**
     * @param \templatable bot_app
     * @return bool|string
     * @throws \moodle_exception
     */
    public function render_bot_app(\templatable $bot) {
        $data = $bot->export_for_template($this);
        return $this->render_from_template('local_cria/bot_app', $data);
    }

    /**
     * @param \templatable translation
     * @return bool|string
     * @throws \moodle_exception
     */
    public function render_translate(\templatable $message) {
        $data = $message->export_for_template($this);
        return $this->render_from_template('local_cria/translate', $data);
    }

    /**
     * @param \templatable $secondopinion
     * @return bool|string
     * @throws \moodle_exception
     */
    public function render_secondopinion(\templatable $message) {
        $data = $message->export_for_template($this);
        return $this->render_from_template('local_cria/secondopinion', $data);
    }

    /**
     * @param \templatable $botpermissions
     * @return bool|string
     * @throws \moodle_exception
     */
    public function render_bot_permissions(\templatable $message) {
        $data = $message->export_for_template($this);
        return $this->render_from_template('local_cria/bot_permissions', $data);
    }

    /**
     * @param \templatable $role
     * @return bool|string
     * @throws \moodle_exception
     */
    public function render_edit_bot_role(\templatable $message) {
        $data = $message->export_for_template($this);
        return $this->render_from_template('local_cria/edit_bot_role', $data);
    }

    /**
     * @param \templatable $role
     * @return bool|string
     * @throws \moodle_exception
     */
    public function render_assign_users(\templatable $message) {
        $data = $message->export_for_template($this);
        return $this->render_from_template('local_cria/assign_users', $data);
    }

    /**
     * @param \templatable $role
     * @return bool|string
     * @throws \moodle_exception
     */
    public function render_intent_questions(\templatable $intent) {
        $data = $intent->export_for_template($this);
        return $this->render_from_template('local_cria/intent_questions', $data);
    }

    /**
     * @param \templatable $role
     * @return bool|string
     * @throws \moodle_exception
     */
    public function render_bot_entities(\templatable $entities) {
        $data = $entities->export_for_template($this);
        return $this->render_from_template('local_cria/bot_entities', $data);
    }
    public function render_bot_keywords(\templatable $keywords) {
        $data = $keywords->export_for_template($this);
        return $this->render_from_template('local_cria/bot_keywords', $data);
    }

}
