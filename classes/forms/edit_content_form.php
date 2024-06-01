<?php

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

        //Header: General
        $mform->addElement(
            'header',
            'add_content_form',
            get_string('add_content', 'local_cria')
        );

        $file_options = [
            'maxbytes' => $CFG->maxbytes,
            'accepted_types' => [
                '.docx', '.pdf',  '.txt',
                '.png', '.jpeg', '.jpg', '.html', '.htm', '.md'
            ]
        ];
        if ($formdata->id) {
            $mform->addElement(
                'filepicker',
                'importedFile', get_string('file', 'local_cria'),
                null,
                $file_options
            );
            $mform->addRule(
                'importedFile',
                get_string('error_importfile', 'local_cria'),
                'required'
            );
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
        }

        // Parsing strategy select menu
        $strategies = base::get_parsing_strategies();
        $mform->addElement(
            'select',
            'parsingstrategy',
            get_string('parse_strategy', 'local_cria'),
            $strategies
        );


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
