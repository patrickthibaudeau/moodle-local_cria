<?php

namespace local_cria;

use local_cria\bot;
use local_cria\bots;
use local_cria\models;

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

        $mform->addElement(
            'header',
            'home-nav-start',
            get_string('bot', 'local_cria')
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
        $mform->addHelpButton(
            'bot_type',
            'bot_type',
            'local_cria'
        );

        // System reserved form element
        if (has_capability('local/cria:edit_system_reserved', $context)) {
            $mform->addElement(
                'selectyesno',
                'system_reserved',
                get_string('system_reserved', 'local_cria')
            );
        }

        // Model id form element
        $mform->addElement(
            'select', 'model_id',
            get_string('model', 'local_cria'),
            $MODELS->get_select_array()
        );
        $mform->setType(
            'model_id',
            PARAM_INT
        );

        // Bot type form element
        $mform->addElement(
            'select',
            'embedding_id',
            get_string('embedding', 'local_cria'),
            $MODELS->get_select_array(true)
        );
        $mform->setType(
            'embedding_id',
            PARAM_INT
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
        $mform->addHelpButton(
            'bot_system_message',
            'bot_system_message',
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

        // User prompt form element
        $mform->addElement(
            'textarea',
            'user_prompt',
            get_string('user_prompt', 'local_cria')
        );
        $mform->setType(
            'user_prompt',
            PARAM_TEXT
        );

        $mform->hideIf(
            'user_prompt',
            'requires_user_prompt',
            'eq',
            0
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
