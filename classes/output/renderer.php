<?php

namespace local_cria\output;

/**
 * Description of renderer
 *
 * @author patrick
 */
class renderer extends \plugin_renderer_base {

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
}
