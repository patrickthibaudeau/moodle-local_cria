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

class edit_content_form extends \moodleform
{

    protected function definition()
    {
        global $CFG, $OUTPUT;

        $formdata = $this->_customdata['formdata'];
        // Create form object
        $mform = &$this->_form;

        $BOT = new bot($formdata->bot_id);

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

        // Set content header
        if ($formdata->id) {
            $mform->addElement(
                'header',
                'edit_content_form',
                get_string('edit_content', 'local_cria')
            );
        } else {
            $mform->addElement(
                'header',
                'add_content_form',
                get_string('add_content', 'local_cria')
            );
        }


        $file_options = [
            'maxbytes' => $CFG->maxbytes,
            'accepted_types' => [
                '.docx', '.pdf',  '.txt',
                '.png', '.jpeg', '.jpg', '.html', '.htm', '.md'
            ]
        ];
        if ($formdata->id) {
//            $mform->addElement(
//                'filepicker',
//                'importedFile', get_string('file', 'local_cria'),
//                null,
//                $file_options
//            );
//            $mform->addRule(
//                'importedFile',
//                get_string('error_importfile', 'local_cria'),
//                'required'
//            );
        } else {
            // Add filemanger element
            $mform->addElement(
                'filemanager',
                'importedFile',
                get_string('files', 'local_cria'),
                null,
                $file_options
            );
            // Required rule
            $mform->addRule(
                'importedFile',
                get_string('error_importfile', 'local_cria'),
                'required'
            );
            // Parsing strategy select menu
            $strategies = base::get_parsing_strategies();
            $mform->addElement(
                'select',
                'parsingstrategy',
                get_string('parse_strategy', 'local_cria'),
                $strategies
            );

        }



        // Keywords multiselect element
        $keywords = $mform->addElement(
            'selectgroups',
            'keywords',
            get_string('keywords', 'local_cria'),
            $BOT->get_available_keywords()
        );
        $keywords->setMultiple(true);


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
