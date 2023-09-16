<?php
namespace local_cria;



defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/lib/formslib.php');
require_once($CFG->dirroot . '/config.php');

class add_content_form extends \moodleform
{

    protected function definition()
    {
        global $DB;

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
            get_string('add_content', 'local_cria')
        );

        $filepickerOptions = [
            'maxbytes' => 130000000,
            'accepted_types' => ['docx', 'pdf']
        ];
        $mform->addElement(
            'filepicker',
            'importedFile', get_string('file', 'local_cria'),
            null,
            $filepickerOptions
        );
        $mform->addRule(
            'importedFile',
            get_string('error_importfile', 'local_cria'),
            'required'
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
