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

use local_cria\provider;
defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/lib/formslib.php');
require_once($CFG->dirroot . '/config.php');

class ms_azure_openai_model_form extends \moodleform
{

    protected function definition()
    {
        global $DB;

        $formdata = $this->_customdata['formdata'];
        // Create form object
        $mform = &$this->_form;

        $PROVIDER = new provider($formdata->provider_id);

        //Hidden id form element
        $mform->addElement(
            'hidden',
            'id'
        );
        $mform->setType(
            'id',
            PARAM_INT
        );

        //Provider id form element
        $mform->addElement(
            'hidden',
            'provider_id'
        );
        $mform->setType(
            'provider_id',
            PARAM_INT
        );

        //Header: General
        $mform->addElement(
            'header',
            'model_form',
            get_string('model', 'local_cria')
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
            'text',
            'api_resource',
            'API resource'
        );
        $mform->setType(
            'api_resource',
            PARAM_TEXT
        );


        // API Version
        $mform->addElement(
            'text',
            'api_version',
            'API version'
        );
        $mform->setType(
            'api_version',
            PARAM_TEXT
        );

        // Azure key
        $mform->addElement(
            'passwordunmask',
            'api_key',
            'api_key'
        );
        $mform->setType(
            'api_key',
            PARAM_TEXT
        );

        // Azure deployment name
        $mform->addElement(
            'text',
            'api_deployment',
            'API Deployment name'
        );
        $mform->setType(
            'api_deployment',
            PARAM_TEXT
        );

        // Modal name
        $mform->addElement(
            'select',
            'api_model',
            'API Model name',
            $PROVIDER->get_llm_models_array()
        );
        $mform->setType(
            'api_model',
            PARAM_TEXT
        );

        // MAx tokens
        $mform->addElement(
            'text',
            'max_tokens',
            'Max tokens'
        );
        $mform->setType(
            'max_tokens',
            PARAM_INT
        );
        // Add help button
        $mform->addHelpButton(
            'max_tokens',
            'model_max_tokens',
            'local_cria'
        );

        // is embedding modal
        $mform->addElement(
            'selectyesno',
            'is_embedding',
            'Embedding model?',
            $PROVIDER->get_llm_models_array()
        );
        $mform->setType(
            'is_embedding',
            PARAM_INT
        );

        // Prompt cost
        $mform->addElement(
            'text',
            'prompt_cost',
            get_string('prompt_cost', 'local_cria')
        );
        $mform->setType(
            'prompt_cost',
            PARAM_FLOAT
        );

        // Prompt cost
        $mform->addElement(
            'text',
            'completion_cost',
            get_string('completion_cost', 'local_cria')
        );
        $mform->setType(
            'completion_cost',
            PARAM_FLOAT
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
