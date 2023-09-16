<?php

namespace local_cria;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/lib/formslib.php');
require_once($CFG->dirroot . '/config.php');

class meeting_master_form extends \moodleform
{

    protected function definition()
    {
        global $DB;

        $formdata = $this->_customdata['formdata'];
        // Create form object
        $mform = &$this->_form;


        // Edit file content
        $mform->addElement(
            'textarea',
            'content',
            get_string('paste_text', 'local_cria'),
            ['rows' => 30]
        );
        $mform->setType(
            'content',
            PARAM_RAW
        );
        // Language you would like the response to be in
        $mform->addElement(
            'select',
            'language',
            get_string('language'),
            [
                'English' => 'English',
                'French' => 'Français'
            ]
        );
        $mform->setType(
            'language',
            PARAM_TEXT
        );
        $mform->setDefault(
            'language',
            'English'
        );
        $mform->addHelpButton(
            'language',
            'language', 'local_cria'
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
