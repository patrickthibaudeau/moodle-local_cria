<?php
namespace local_cria;

use local_cria\base;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/lib/formslib.php');
require_once($CFG->dirroot . '/config.php');

class edit_intent_form extends \moodleform
{

    protected function definition()
    {
        global $DB, $OUTPUT;

        $formdata = $this->_customdata['formdata'];
        // Create form object
        $mform = &$this->_form;

        $mform->addElement(
            'hidden',
            'id'
        );
        $mform->setType(
            'id',
            PARAM_INT
        );
        $mform->addElement(
            'hidden',
            'bot_id'
        );
        $mform->setType(
            'bot_id',
            PARAM_INT
        );

        //Header: General
        $mform->addElement(
            'header',
            'add_content_form',
            get_string('edit_intent', 'local_cria')
        );

        // Add name element to form
        $mform->addElement(
            'text',
            'name',
            get_string('name', 'local_cria')
        );
        $mform->setType(
            'name',
            PARAM_TEXT
        );
        $mform->addRule(
            'name',
            get_string('required'),
            'required',
            null,
            'client'
        );

        // Add description element to form
        $mform->addElement(
            'textarea',
            'description',
            get_string('description', 'local_cria')
        );
        $mform->setType(
            'description',
            PARAM_TEXT
        );
        $mform->addRule(
            'description',
            get_string('required'),
            'required',
            null,
            'client'
        );
        // Add published element to form
        $mform->addElement(
            'selectyesno',
            'published',
            get_string('publish', 'local_cria')
        );
        $mform->setType(
            'published',
            PARAM_INT
        );


        $mform->addElement(
            'html',
            '<h3>Default audience settings</h3>'
        );

        // Lang form element
        $mform->addElement(
            'select',
            'lang',
            get_string('language', 'local_cria'),
            base::get_languages()
        );
        $mform->setType(
            'lang',
            PARAM_TEXT
        );

        // Faculty form element
        $mform->addElement(
            'select',
            'faculty',
            get_string('faculty', 'local_cria'),
            base::get_faculties()
        );
        $mform->setType(
            'faculty',
            PARAM_TEXT
        );

        // Programs form element
        $mform->addElement(
            'select',
            'program',
            get_string('program', 'local_cria'),
            base::get_programs()
        );
        $mform->setType(
            'program',
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
