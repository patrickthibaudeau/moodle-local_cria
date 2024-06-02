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

class bot_type_form extends \moodleform
{

    protected function definition()
    {
        global $DB;

        $formdata = $this->_customdata['formdata'];
        // Create form object
        $mform = &$this->_form;

        //Hidden id form element
        $mform->addElement(
            'hidden',
            'id'
        );
        $mform->setType(
            'id',
            PARAM_INT
        );

        //Header: General
        $mform->addElement(
            'header',
            'bot_type_form',
            get_string('bot_type', 'local_cria')
        );

        // Name form element
        $mform->addElement(
            'text',
            'name',
            get_string('name', 'local_cria')
        );
        $mform->setType(
            'name',
            PARAM_TEXT
        );
        // Description form element
        $mform->addElement(
            'textarea',
            'description',
            get_string('description', 'local_cria')
        );


        // Use indexing server form element
        $mform->addElement(
            'selectyesno',
            'use_bot_server',
            get_string('use_bot_server', 'local_cria')
        );
        $mform->addHelpButton(
            'use_bot_server',
            'use_bot_server',
            'local_cria'
        );
        $mform->setType(
            'use_bot_server',
            PARAM_INT
        );

        // Description form element
        $mform->addElement(
            'textarea',
            'system_message',
            get_string('system_message', 'local_cria'),
            ['rows' => 20]
        );
        $mform->setType(
            'description',
            PARAM_TEXT);

        $this->add_action_buttons();
        $this->set_data($formdata);
    }

    // Perform some extra moodle validation
    public function validation($data, $files)
    {
        global $DB;

        $errors = parent::validation($data, $files);

//        if (is_null($data['id'])) {
//            $id = -1;
//        } else {
//            $id = $data['id'];
//        }
//
//        if ($data['yulearncategoryid'] == 0) {
//            $errors['yulearncategoryid'] = get_string('field_required', 'local_yulearn');
//        }
//
//        if ($data['id'] < 1) {
//            $sql = 'SELECT * FROM {yulearn_course} WHERE shortname = "'
//                . trim($data['shortname']) . '" AND '
//                . 'id != ' . $id;
//            if ($foundcourses = $DB->get_records_sql($sql)) {
//
//                if (!empty($foundcourses)) {
//                    foreach ($foundcourses as $foundcourse) {
//                        $foundcoursenames[] = $foundcourse->fullname;
//                    }
//                    $foundcoursenamestring = implode(',', $foundcoursenames);
//                    $errors['shortname'] = get_string('shortnametaken', '', $foundcoursenamestring);
//                }
//            }
//
//            if ($foundMoodleCourse = $DB->get_record('course', ['shortname' => trim($data['shortname'])])) {
//                $errors['shortname'] = get_string('shortnametaken');
//            }
//
//
//            $sql = 'SELECT * FROM {yulearn_course} WHERE externalcode = "'
//                . trim($data['externalcode']) . '" AND '
//                . 'id != ' . $id;
//            if ($foundcourses = $DB->get_records_sql($sql)) {
//
//                if (!empty($foundcourses)) {
//                    foreach ($foundcourses as $foundcourse) {
//                        $foundcoursenames[] = $foundcourse->fullname;
//                    }
//                    $foundcoursenamestring = implode(',', $foundcoursenames);
//                    $errors['externalcode'] = get_string('externalcode_taken', 'local_yulearn', $foundcoursenamestring);
//                }
//            }
//
//            if ($data['hascertificate']) {
//                if (!$data['certificatetemplateid']) {
//                    $errors['certificatetemplateid'] = get_string('required', 'local_yulearn');
//                }
//            }
//        }
//
//        // Certificate notifications
//        for ($i = 0; $i < count($data['rnotificationruleid']); $i++) {
//            if ($data['remailtemplateid'][$i] == 0) {
//                $errors['remailtemplateid'][$i] = get_string('required', 'local_yulearn');
//            }
//        }
//
//        for ($i = 0; $i < count($data['remailtemplateid']); $i++) {
//            if (isset($data['rnotificationruleid'])) {
//                if ($data['rnotificationruleid'][$i] == 0) {
//                    $errors['rnotificationruleid'][$i] = get_string('required', 'local_yulearn');
//                }
//            } else {
//                $errors['rnotificationruleid'][$i]  = get_string('required', 'local_yulearn');
//            }
//        }

        return $errors;
    }

}
