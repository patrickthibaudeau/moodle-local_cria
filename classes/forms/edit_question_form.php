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

        $context = \context_system::instance();
        $BOT = new bot($formdata->bot_id);

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
            'intent_id'
        );
        $mform->setType(
            'intent_id',
            PARAM_INT
        );
        // Add parent_id element
        $mform->addElement(
            'hidden',
            'parent_id'
        );
        $mform->setType(
            'parent_id',
            PARAM_INT
        );

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

        // Add value element to form
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
            'editor',
            'answereditor',
            get_string('answer', 'local_cria'),
            null,
            base::getEditorOptions($context)
        );
        $mform->setType(
            'answereditor',
            PARAM_RAW
        );
        $mform->addRule(
            'answereditor',
            get_string('required'),
            'required',
            null,
            'client'
        );

        // Add yes/no select element to generate_answer
        $mform->addElement(
            'selectyesno',
            'generate_answer',
            get_string('generate_answer', 'local_cria')
        );
        $mform->setType(
            'generate_answer',
            PARAM_INT
        );
        // Add help button
        $mform->addHelpButton(
            'generate_answer',
            'generate_answer',
            'local_cria'
        );

        // Keywords multiselect element
        $keywords = $mform->addElement(
            'selectgroups',
            'keywords',
            get_string('keywords', 'local_cria'),
            $BOT->get_available_keywords()
        );
        $keywords->setMultiple(true);

        $mform->addElement(
            'html',
            '<h3>' . get_string('automated_tasks', 'local_cria') . '</h3>'
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

        $buttonarray=array();
        $buttonarray[] = $mform->createElement(
            'submit',
            'submitbutton',
            get_string('savechanges')
        );
        $buttonarray[] = $mform->createElement(
            'cancel',
            'cancel',
            get_string('return', 'local_cria')
        );
        $mform->addGroup(
            $buttonarray,
            'buttonar',
            '',
            ' ',
            false
        );
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
