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

class edit_provider_form extends \moodleform
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
            'header',
            'home-nav-start',
            get_string('provider', 'local_cria')
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
        $mform->addRule(
            'name',
            get_string('required'),
            'required',
            null,
            'client'
        );
        // Name form element
        $mform->addElement(
            'text',
            'idnumber',
            get_string('idnumber', 'core')
        );
        $mform->setType(
            'idnumber', PARAM_TEXT
        );
        $mform->addRule(
            'idnumber',
            get_string('required'),
            'required',
            null,
            'client'
        );

        $mform->addElement(
            'textarea',
            'llm_models',
            get_string('llm_models', 'local_cria')
        );
        $mform->setType(
            'llm_models', PARAM_TEXT
        );
        $mform->addRule(
            'llm_models',
            get_string('required'),
            'required',
            null,
            'client'
        );

        // Description form element
        $mform->addElement(
            'filemanager',
            'provider_image',
            get_string('provider_image', 'local_cria')
        );
        $mform->setType(
            'provider_image',
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
