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

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/lib/formslib.php');
require_once($CFG->dirroot . '/config.php');

class edit_keyword_form extends \moodleform
{

    protected function definition()
    {
        global $DB, $OUTPUT;

        $formdata = $this->_customdata['formdata'];
        // Create form object
        $mform = &$this->_form;

        $context = \context_system::instance();

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
            'entity_id'
        );
        $mform->setType(
            'entity_id',
            PARAM_INT
        );



        // If there is a record
        if ($formdata->id) {
            // add row and col-md-6
            $html = '<div id="example-sysnonyms-container" class="row">';
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
            'value',
            get_string('keyword', 'local_cria')
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


        $mform->addElement(
            'html',
            '<h3>' . get_string('automated_tasks', 'local_cria') . '</h3>'
        );

        // Generate synonyms
        $mform->addElement(
            'selectyesno',
            'generate_synonyms',
            get_string('generate_synonyms', 'local_cria')
        );
        $mform->setType(
            'generate_synonyms',
            PARAM_INT
        );
        // Add help button
        $mform->addHelpButton(
            'generate_synonyms',
            'generate_synonyms',
            'local_cria'
        );
        // If there is a record
        if ($formdata->id) {
            $html = '</div>';
            $mform->addElement(
                'html',
                $html
            );
            // Add examples base on template question_examples
            $synonyms = $OUTPUT->render_from_template(
                'local_cria/synonyms',
                array(
                    'synonyms' => $formdata->synonyms,
                )
            );
            $mform->addElement(
                'html',
                $synonyms . "\n" .
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
