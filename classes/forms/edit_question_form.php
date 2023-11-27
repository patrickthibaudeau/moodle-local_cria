<?php
namespace local_cria;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/lib/formslib.php');
require_once($CFG->dirroot . '/config.php');

class edit_question_form extends \moodleform
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
        // Add itentid element to form
        $mform->addElement(
            'hidden',
            'intentid'
        );
        $mform->setType(
            'intentid',
            PARAM_INT
        );

//        //Header: General
//        $mform->addElement(
//            'header',
//            'add_content_form',
//            get_string('edit_question', 'local_cria')
//        );

        // If there is a record
        if ($formdata->id) {
            // add row and col-md-6
            $html = '<div id="example-questions-container" class="row">';
            $html .= '<div class="col-md-6">';
            // add html element
            $mform->addElement(
                'html',
                $html
            );
        }
        // Add name element to form
        $mform->addElement(
            'textarea',
            'value',
            get_string('question', 'local_cria')
        );
        $mform->setType(
            'value',
            PARAM_TEXT
        );
        $mform->addRule(
            'value',
            get_string('required'),
            'required',
            null,
            'client'
        );

        // Add answer field
        $mform->addElement(
            'textarea',
            'answer',
            get_string('answer', 'local_cria')
        );
        $mform->setType(
            'answer',
            PARAM_TEXT
        );
        $mform->addRule(
            'answer',
            get_string('required'),
            'required',
            null,
            'client'
        );

        // Ask if user wnats to create automatic example questions
        $mform->addElement(
            'selectyesno',
            'create_example_questions',
            get_string('create_example_questions', 'local_cria')
        );
        $mform->setType(
            'create_example_questions',
            PARAM_INT
        );
        // If there is a record
        if ($formdata->id) {
            $html = '</div>';
            $mform->addElement(
                'html',
                $html
            );
            // Add examples base on template question_examples
            $example_questions = $OUTPUT->render_from_template(
                'local_cria/question_examples',
                array(
                    'examples' => $formdata->examples,
                )
            );
            $mform->addElement(
                'html',
                $example_questions . "\n" .
                '</div>' //row example-questions-container
            );
        }

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
