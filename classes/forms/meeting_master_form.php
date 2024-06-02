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
