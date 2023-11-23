<?php

namespace local_cria;

use local_cria\bot;
use local_cria\bots;
use local_cria\models;
use local_cria\conversation_styles;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/lib/formslib.php');
require_once($CFG->dirroot . '/config.php');

class bot_form extends \moodleform
{

    protected function definition()
    {
        global $DB, $OUTPUT;

        $formdata = $this->_customdata['formdata'];
        // Create form object
        $mform = &$this->_form;

        $context = \context_system::instance();

        $MODELS = new models();

        $mform->addElement(
            'hidden',
            'id'
        );
        $mform->setType(
            'id',
            PARAM_INT
        );

        // Html element as a header for About fields
        $mform->addElement(
            'html',
            '<h3>' . get_string('about', 'local_cria') . '</h3><hr>'
        );
        // Name form element
        $mform->addElement(
            'text',
            'name',
            get_string('name', 'local_cria')
        );
        $mform->setType(
            'name', PARAM_TEXT
        );

        // Add rule required for name
        $mform->addRule(
            'name',
            get_string('required'),
            'required',
            null,
            'client'
        );

        // Description form element
        $mform->addElement(
            'editor',
            'description_editor',
            get_string('description', 'local_cria')
        );
        $mform->setType(
            'description',
            PARAM_RAW
        );

        // Html element as a header for Bot personality
        $mform->addElement(
            'html',
            '<h3>' . get_string('bot_personality', 'local_cria') . '</h3><hr>'
        );
        // Bot type form element
        $mform->addElement(
            'select',
            'bot_type',
            get_string('bot_type', 'local_cria'),
            bots::get_bot_types()
        );

        $mform->setType(
            'bot_type',
            PARAM_INT
        );
        // Add rule required for bot type
        $mform->addRule(
            'bot_type',
            get_string('required'),
            'required',
            null,
            'client'
        );
        $mform->addHelpButton(
            'bot_type',
            'bot_type',
            'local_cria'
        );

        if (has_capability('local/cria:view_advanced_bot_options', $context)) {
            // Bot system message form element
            $mform->addElement(
                'textarea',
                'bot_system_message',
                get_string('bot_system_message', 'local_cria')
            );

        } else {
            // Add hiddedn element to store the bot system message
            $mform->addElement(
                'hidden',
                'bot_system_message'
            );
        }
        $mform->setType(
            'bot_system_message',
            PARAM_TEXT
        );

        // No context message form element
        $mform->addElement(
            'textarea',
            'no_context_message',
            get_string('no_context_message', 'local_cria')
        );
        $mform->setType(
            'no_context_message',
            PARAM_TEXT
        );

        // Model id form element
        $mform->addElement(
            'select', 'model_id',
            get_string('chatbot_framework', 'local_cria'),
            $MODELS->get_select_array()
        );
        $mform->setType(
            'model_id',
            PARAM_INT
        );
        // Add rule required for model id
        $mform->addRule(
            'model_id',
            get_string('required'),
            'required',
            null,
            'client'
        );

        // Bot type form element
        $mform->addElement(
            'select',
            'embedding_id',
            get_string('bot_content_training_framework', 'local_cria'),
            $MODELS->get_select_array(true)
        );
        $mform->setType(
            'embedding_id',
            PARAM_INT
        );

        // Add rule required for embedding id
        $mform->addRule(
            'embedding_id',
            get_string('required'),
            'required',
            null,
            'client'
        );

        if (has_capability('local/cria:view_advanced_bot_options', $context)) {
            // Max tokens form element
            $mform->addElement(
                'text',
                'max_tokens',
                get_string('max_tokens', 'local_cria'),
                ['style' => 'width: 100px;']
            );
            // Add help button
            $mform->addHelpButton(
                'max_tokens',
                'max_tokens',
                'local_cria'
            );
            // temperature form element
            $mform->addElement(
                'text',
                'temperature',
                get_string('temperature', 'local_cria'),
                ['style' => 'width: 100px;']
            );
            // Add help button
            $mform->addHelpButton(
                'temperature',
                'temperature',
                'local_cria'
            );
            // top_p form element
            $mform->addElement(
                'text',
                'top_p',
                get_string('top_p', 'local_cria'),
                ['style' => 'width: 100px;']
            );
            // Add help button
            $mform->addHelpButton(
                'top_p',
                'top_p',
                'local_cria'
            );
            // top_k form element
            $mform->addElement(
                'text',
                'top_k',
                get_string('top_k', 'local_cria'),
                ['style' => 'width: 100px;']
            );
            // Add help button
            $mform->addHelpButton(
                'top_k',
                'top_k',
                'local_cria'
            );
            // Minimum relevance form element
            $mform->addElement(
                'text',
                'minimum_relevance',
                get_string('minimum_relevance', 'local_cria'),
                ['style' => 'width: 100px;']
            );
            // Add help button
            $mform->addHelpButton(
                'minimum_relevance',
                'minimum_relevance',
                'local_cria'
            );
            // Max context form element
            $mform->addElement(
                'text',
                'max_context',
                get_string('max_context', 'local_cria'),
                ['style' => 'width: 100px;']
            );
            // Add help button
            $mform->addHelpButton(
                'max_context',
                'max_context',
                'local_cria'
            );

        } else {
            // Set all elements to hidden
            $mform->addElement(
                'hidden',
                'max_tokens'
            );
            $mform->addElement(
                'hidden',
                'temperature'
            );
            $mform->addElement(
                'hidden',
                'top_p'
            );
            $mform->addElement(
                'hidden',
                'top_k'
            );
            $mform->addElement(
                'hidden',
                'minimum_relevance'
            );
            $mform->addElement(
                'hidden',
                'max_context'
            );

            $mform->addElement(
                'html',
                '<div id="cria-tone-buttons" style="display: none;">'
            );
            // Add tone buttons
            $CONVO_STYLES = new conversation_styles();
            $tone_buttons = $OUTPUT->render_from_template(
                'local_cria/tone_buttons',
                $CONVO_STYLES->get_tone_buttons()
            );
            // Add html form element
            $mform->addElement(
                'html',
                $tone_buttons
            );

            // Add length buttons
            $length_buttons = $OUTPUT->render_from_template(
                'local_cria/length_buttons',
                []
            );
            // Add html form element
            $mform->addElement(
                'html',
                $length_buttons
            );

            $mform->addElement(
                'html',
                '</div>'
            );
        }

        $mform->setType(
            'max_tokens',
            PARAM_INT
        );
        $mform->setType(
            'temperature',
            PARAM_FLOAT
        );
        $mform->setType(
            'top_p',
            PARAM_FLOAT
        );
        $mform->setType(
            'top_k',
            PARAM_FLOAT
        );
        $mform->setType(
            'minimum_relevance',
            PARAM_INT
        );
        $mform->setType(
            'max_context',
            PARAM_INT
        );


        // System reserved form element
        if (has_capability('local/cria:view_advanced_bot_options', $context)) {
            // Html element as a header for Bot personality
            $mform->addElement(
                'html',
                '<h3>' . get_string('advanced_settings', 'local_cria') . '</h3><hr>'
            );
            $mform->addElement(
                'selectyesno',
                'system_reserved',
                get_string('system_reserved', 'local_cria')
            );

            // Plugin path
            $mform->addElement(
                'text',
                'plugin_path',
                get_string('plugin_path', 'local_cria')
            );
            $mform->setType(
                'plugin_path',
                PARAM_TEXT
            );
        }


        // Html element as a header for prompts
        $mform->addElement(
            'html',
            '<h3>' . get_string('prompt_settings', 'local_cria') . '</h3><hr>'
        );

        $mform->addHelpButton(
            'bot_system_message',
            'bot_system_message',
            'local_cria'
        );

        // Requires content prompt form element
        $mform->addElement(
            'selectyesno',
            'requires_content_prompt',
            get_string('requires_content_prompt', 'local_cria')
        );
        $mform->setType(
            'requires_content_prompt',
            PARAM_INT
        );
        $mform->addHelpButton(
            'requires_content_prompt',
            'requires_content_prompt',
            'local_cria'
        );

        // Requires user prompt form element
        $mform->addElement(
            'selectyesno',
            'requires_user_prompt',
            get_string('requires_user_prompt', 'local_cria')
        );
        $mform->setType(
            'requires_user_prompt',
            PARAM_INT
        );
        $mform->addHelpButton(
            'requires_user_prompt',
            'requires_user_prompt',
            'local_cria'
        );

        // User prompt form element
        $mform->addElement(
            'textarea',
            'user_prompt',
            get_string('default_user_prompt', 'local_cria')
        );
        $mform->setType(
            'user_prompt',
            PARAM_TEXT
        );
        $mform->addHelpButton(
            'user_prompt',
            'default_user_prompt',
            'local_cria'
        );

        // Publish form element
        $mform->addElement(
            'selectyesno',
            'publish',
            get_string('publish', 'local_cria')
        );
        $mform->setType(
            'publish',
            PARAM_INT
        );

        $mform->addElement(
            'header',
            'display-settings-nav-start',
            get_string('display_settings', 'local_cria')
        );
        // Welcome message element
        $mform->addElement(
            'textarea',
            'welcome_message',
            get_string('welcome_message', 'local_cria')
        );
        $mform->setType(
            'welcome_message',
            PARAM_RAW
        );

        // Welcome message element
        $mform->addElement(
            'text',
            'theme_color',
            get_string('theme_color', 'local_cria'),
            ['data-jscolor' => '', 'style' => 'width: 150px;']
        );
        $mform->setType(
            'theme_color',
            PARAM_TEXT
        );


        $this->add_action_buttons();
        $this->set_data($formdata);
    }

    // Perform some extra moodle validation
    public function validation($data, $files)
    {
        global $DB;

        $errors = parent::validation($data, $files);


        return $errors;
    }

}
