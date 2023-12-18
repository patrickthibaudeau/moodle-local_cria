<?php
namespace local_cria;



defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/lib/formslib.php');
require_once($CFG->dirroot . '/config.php');

class add_content_form extends \moodleform
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
        // Intent id
        $mform->addElement(
            'hidden',
            'intent_id'
        );
        $mform->setType(
            'intent_id',
            PARAM_INT
        );

        //bot_id
        $mform->addElement(
            'hidden',
            'bot_id'
        );
        $mform->setType(
            'bot_id',
            PARAM_INT
        );

        $mform->addElement(
            'hidden',
            'name'
        );
        $mform->setType(
            'name',
            PARAM_TEXT
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
        $mform->addElement(
            'html',
            '<h3>' . get_string('audience', 'local_cria') . '</h3>'
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

        // Add HTML Element to output template content_form_progress_bars
        $mform->addElement(
            'html',
            $OUTPUT->render_from_template(
                'local_cria/content_form_progress_bars',
                []
            )
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
