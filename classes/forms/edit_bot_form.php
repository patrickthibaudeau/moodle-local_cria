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



namespace local_cria;

use local_cria\base;
use local_cria\bot;
use local_cria\bots;
use local_cria\models;
use local_cria\conversation_styles;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/lib/formslib.php');
require_once($CFG->dirroot . '/config.php');

class edit_bot_form extends \moodleform
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

        // return element
        $mform->addElement(
            'hidden',
            'return'
        );
        $mform->setType(
            'return',
            PARAM_TEXT
        );

        // Bot id element
        $mform->addElement(
            'hidden',
            'bot_id'
        );
        $mform->setType(
            'bot_id',
            PARAM_INT
        );

        // bot max tokens element
        $mform->addElement(
            'hidden',
            'bot_max_tokens'
        );
        $mform->setType(
            'bot_max_tokens',
            PARAM_INT
        );

        // add tone hidden element
        $mform->addElement(
            'hidden',
            'tone'
        );
        $mform->setType(
            'tone',
            PARAM_TEXT
        );

        // add length hidden element
        $mform->addElement(
            'hidden',
            'response_length'
        );
        $mform->setType(
            'response_length',
            PARAM_TEXT
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

        // Add select yes/no element fora available_child
        $mform->addElement(
            'selectyesno',
            'available_child',
            get_string('available_child', 'local_cria')
        );
        $mform->setType(
            'available_child',
            PARAM_INT
        );
        // Add help button
        $mform->addHelpButton(
            'available_child',
            'available_child',
            'local_cria'
        );

        // Html element as a header for Bot personality
        $mform->addElement(
            'html',
            '<h3>' . get_string('bot_personality', 'local_cria') . '</h3><hr>'
        );
        // Bot type form element
        if (is_siteadmin()) {
            // Parse strategy select element
            $mform->addElement(
                'select',
                'parse_strategy',
                get_string('parse_strategy', 'local_cria'),
                base::get_parsing_strategies()
            );
            // Add rule required for parse strategy
            $mform->addRule(
                'parse_strategy',
                get_string('required'),
                'required',
                null,
                'client'
            );
            // Add help button
            $mform->addHelpButton(
                'parse_strategy',
                'parse_strategy',
                'local_cria'
            );

            $mform->addElement(
                'select',
                'bot_type',
                get_string('bot_type', 'local_cria'),
                bots::get_bot_types()
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


        } else {
            $mform->addElement(
                'hidden',
                'bot_type'
            );

            $mform->addElement(
                'hidden',
                'parse_strategy'
            );
        }
        $mform->setType(
            'bot_type',
            PARAM_INT
        );

        $mform->setType(
            'parse_strategy',
            PARAM_TEXT
        );

        // Bot system message form element
        $mform->addElement(
            'textarea',
            'bot_system_message',
            get_string('bot_system_message', 'local_cria')
        );
        $mform->setType(
            'bot_system_message',
            PARAM_TEXT
        );

        // No context use message form element
        $mform->addElement(
            'selectyesno',
            'no_context_use_message',
            get_string('no_context_use_message', 'local_cria')
        );
        $mform->setType(
            'no_context_use_message',
            PARAM_INT
        );
        // Add help button
        $mform->addHelpButton(
            'no_context_use_message',
            'no_context_use_message',
            'local_cria'
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

        // Set rule hide if no_context_use_message is set to 0
        $mform->hideIf(
            'no_context_message',
            'no_context_use_message',
            'eq',
            0
        );

        // Add email form element
        $mform->addElement(
            'text',
            'email',
            get_string('no_context_email', 'local_cria')
        );
        $mform->setType(
            'email',
            PARAM_TEXT
        );
        // Add help button
        $mform->addHelpButton(
            'email',
            'no_context_email',
            'local_cria'
        );

        // No context llm guess element
        $mform->addElement(
            'selectyesno',
            'no_context_llm_guess',
            get_string('no_context_llm_guess', 'local_cria')
        );
        $mform->setType(
            'no_context_llm_guess',
            PARAM_INT
        );
        // Add help button
        $mform->addHelpButton(
            'no_context_llm_guess',
            'no_context_llm_guess',
            'local_cria'
        );

        if (is_siteadmin()) {
            // Model id form element
            $mform->addElement(
                'select', 'model_id',
                get_string('chatbot_framework', 'local_cria'),
                $MODELS->get_select_array()
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

            // Add rule required for embedding id
            $mform->addRule(
                'embedding_id',
                get_string('required'),
                'required',
                null,
                'client'
            );

            // Add rerank model is
            $mform->addElement(
                'select',
                'rerank_model_id',
                get_string('rerank_model_id', 'local_cria'),
                $MODELS->get_select_array(false, true)
            );
            // Add rule required for rerank model id
            $mform->addRule(
                'rerank_model_id',
                get_string('required'),
                'required',
                null,
                'client'
            );
        } else {
            $mform->addElement(
                'hidden',
                'model_id'
            );
            $mform->addElement(
                'hidden',
                'embedding_id'
            );
            $mform->addElement(
                'hidden',
                'rerank_model_id'
            );
        }

        $mform->setType(
            'model_id',
            PARAM_INT
        );

        $mform->setType(
            'embedding_id',
            PARAM_INT
        );

        $mform->setType(
            'rerank_model_id',
            PARAM_INT
        );


        if (is_siteadmin()) {
            // Add fine_tuning element
            $mform->addElement(
                'selectyesno',
                'fine_tuning',
                get_string('fine_tuning', 'local_cria')
            );
            $mform->setType(
                'fine_tuning',
                PARAM_INT
            );
            // Add help button
            $mform->addHelpButton(
                'fine_tuning',
                'fine_tuning',
                'local_cria'
            );
        }


        // Display default param buttons
        if ($formdata->model_id) {
            $mform->addElement(
                'html',
                '<div id="cria-default-param-buttons">'
            );
        } else {
            $mform->addElement(
                'html',
                '<div id="cria-default-param-buttons" style="display: none;">'
            );
        }
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


//        if (has_capability('local/cria:view_advanced_bot_options', $context)) {
        if (is_siteadmin()) {
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
            // Hide element unless fine_tuning is set to 1
            $mform->hideIf(
                'max_tokens',
                'fine_tuning',
                'eq',
                0
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
            // Hide element unless fine_tuning is set to 1
            $mform->hideIf(
                'temperature',
                'fine_tuning',
                'eq',
                0
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

            // Hide element unless fine_tuning is set to 1
            $mform->hideIf(
                'top_p',
                'fine_tuning',
                'eq',
                0
            );

            // top_k form element
            $mform->addElement(
                'text',
                'top_k',
                get_string('top_k', 'local_cria'),
                ['style' => 'width: 100px;']
            );
            // Set type
            $mform->setType(
                'top_k',
                PARAM_INT
            );

            // Add help button
            $mform->addHelpButton(
                'top_k',
                'top_k',
                'local_cria'
            );
            // Hide element unless fine_tuning is set to 1
            $mform->hideIf(
                'top_k',
                'fine_tuning',
                'eq',
                0
            );

            // add element for top_n
            $mform->addElement(
                'text',
                'top_n',
                get_string('top_n', 'local_cria'),
                ['style' => 'width: 100px;']
            );
            // Set type
            $mform->setType(
                'top_n',
                PARAM_INT
            );
            // Hide element unless fine_tuning is set to 1
            $mform->hideIf(
                'top_n',
                'fine_tuning',
                'eq',
                0
            );

            // Add elemnet for min_k
            $mform->addElement(
                'text',
                'min_k',
                get_string('min_k', 'local_cria'),
                ['style' => 'width: 100px;']
            );
            // Set type
            $mform->setType(
                'min_k',
                PARAM_FLOAT
            );
            // Hide element unless fine_tuning is set to 1
            $mform->hideIf(
                'min_k',
                'fine_tuning',
                'eq',
                0
            );

            // Minimum relevance form element
            $mform->addElement(
                'text',
                'min_relevance',
                get_string('min_relevance', 'local_cria'),
                ['style' => 'width: 100px;']
            );

            // Add help button
            $mform->addHelpButton(
                'min_relevance',
                'min_relevance',
                'local_cria'
            );
            // Hide element unless fine_tuning is set to 1
            $mform->hideIf(
                'min_relevance',
                'fine_tuning',
                'eq',
                0
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
            // Hide element unless fine_tuning is set to 1
            $mform->hideIf(
                'max_context',
                'fine_tuning',
                'eq',
                0
            );
        } else {
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
                'min_relevance'
            );
            $mform->addElement(
                'hidden',
                'max_context'
            );
        }

        $mform->setType(
            'max_context',
            PARAM_INT
        );

        $mform->setType(
            'min_relevance',
            PARAM_FLOAT
        );

        $mform->setType(
            'min_rel',
            PARAM_INT
        );

        $mform->setType(
            'top_p',
            PARAM_FLOAT
        );

        $mform->setType(
            'temperature',
            PARAM_FLOAT
        );

        $mform->setType(
            'max_tokens',
            PARAM_INT
        );

        // Add multi select element for child_bots
        $child_bots = $mform->addElement(
            'select',
            'child_bots',
            get_string('child_bots', 'local_cria'),
            base::get_available_child_bots($formdata->id)
        );
        $child_bots->setMultiple(true);
        $mform->setType(
            'child_bots',
            PARAM_RAW
        );
        // Add help button
        $mform->addHelpButton(
            'child_bots',
            'child_bots',
            'local_cria'
        );

        $mform->addElement(
            'html',
            '</div>'
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

        if (is_siteadmin()) {
            // Requires content prompt form element
            $mform->addElement(
                'selectyesno',
                'requires_content_prompt',
                get_string('requires_content_prompt', 'local_cria')
            );

            $mform->addHelpButton(
                'requires_content_prompt',
                'requires_content_prompt',
                'local_cria'
            );
        } else {
            $mform->addElement(
                'hidden',
                'requires_content_prompt'
            );
        }
        $mform->setType(
            'requires_content_prompt',
            PARAM_INT
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

        if (is_siteadmin()) {
            // Publish form element
            $mform->addElement(
                'selectyesno',
                'publish',
                get_string('publish', 'local_cria')
            );
        } else {
            $mform->addElement(
                'hidden',
                'publish'
            );
        }
        $mform->setType(
            'publish',
            PARAM_INT
        );

        $mform->addElement(
            'header',
            'display-settings-nav-start',
            get_string('display_settings', 'local_cria')
        );

        // Add title element
        $mform->addElement(
            'text',
            'title',
            get_string('title', 'local_cria')
        );
        $mform->setType(
            'title',
            PARAM_TEXT
        );

        // Add subtitle element
        $mform->addElement(
            'text',
            'subtitle',
            get_string('subtitle', 'local_cria')
        );
        $mform->setType(
            'subtitle',
            PARAM_TEXT
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

        // Add icon url element
        $mform->addElement(
            'filemanager',
            'icon_url',
            get_string('icon_url', 'local_cria'),
            null,
            ['subdirs' => 0, 'maxbytes' => 0, 'maxfiles' => 1, 'accepted_types' => ['image']]
        );
        $mform->setType(
            'icon_url',
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

        // Add embed_enabled element
        $mform->addElement(
            'selectyesno',
            'embed_enabled',
            get_string('embed_enabled', 'local_cria')
        );
        $mform->setType(
            'embed_enabled',
            PARAM_INT
        );
        // Add help button
        $mform->addHelpButton(
            'embed_enabled',
            'embed_enabled',
            'local_cria'
        );

        $positions = [
            1 => get_string('bottom_left', 'local_cria'),
            2 => get_string('bottom_right', 'local_cria'),
            3 => get_string('top_right', 'local_cria'),
            4 => get_string('top_left', 'local_cria'),
        ];
        // Add embed_position element
        $mform->addElement(
            'select',
            'embed_position',
            get_string('embed_position', 'local_cria'),
            $positions
        );
        $mform->setType(
            'embed_position',
            PARAM_INT
        );
        // Add help button
        $mform->addHelpButton(
            'embed_position',
            'embed_position',
            'local_cria'
        );

        // add selectyesno element for botwatermark
        $mform->addElement(
            'selectyesno',
            'botwatermark',
            get_string('bot_watermark', 'local_cria')
        );
        $mform->setType(
            'bot_watermark',
            PARAM_INT
        );
        // Add help button
        $mform->addHelpButton(
            'botwatermark',
            'bot_watermark',
            'local_cria'
        );

        $locales = [
            'en-US' => 'English',
            'fr-CA' => 'Français',
        ];

        // Add select element for bot_locale
        $mform->addElement(
            'select',
            'bot_locale',
            get_string('bot_locale', 'local_cria'),
            $locales
        );
        $mform->setType(
            'bot_locale',
            PARAM_TEXT
        );
        // Add help button
        $mform->addHelpButton(
            'bot_locale',
            'bot_locale',
            'local_cria'
        );

        // Add a header for development
        $mform->addElement(
            'html',
            '<h3>' . get_string('for_developers', 'local_cria') . '</h3><hr>'
        );

        // Add HTML alert instructions about the bot API key and bot name
        $mform->addElement(
            'html',
            '<div class="alert alert-warning" role="alert">
                <h4 class="alert-heading">Important</h4>
                <p>'
            . get_string('bot_api_key_instructions', 'local_cria')
            . ' </p>
            </div>'
        );

        $mform->addElement(
            'passwordunmask',
            'bot_api_key',
            get_string('bot_api_key', 'local_cria')
        );
        $mform->setType(
            'bot_api_key',
            PARAM_TEXT
        );



        // Add help button
        $mform->addHelpButton(
            'bot_api_key',
            'bot_api_key',
            'local_cria'
        );

        // Add bot name element
        $mform->addElement(
            'text',
            'bot_name',
            get_string('bot_name', 'local_cria')
        );
        $mform->setType(
            'bot_name',
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
