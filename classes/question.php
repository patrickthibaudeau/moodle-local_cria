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


/*
 * Author: Admin User
 * Create Date: 27-01-2024
 * License: LGPL 
 * 
 */

namespace local_cria;

use local_cria\crud;

class question extends crud
{


    /**
     *
     * @var int
     */
    private $id;

    /**
     *
     * @var int
     */
    private $intent_id;

    /**
     *
     * @var int
     */
    private $parent_id;

    /**
     *
     * @var string
     */
    private $document_name;

    /**
     *
     * @var string
     */
    private $name;

    /**
     *
     * @var string
     */
    private $value;

    /**
     *
     * @var string
     */
    private $answer;

    /**
     *
     * @var string
     */
    private $keywords;

    /**
     *
     * @var int
     */
    private $published;

    /**
     *
     * @var string
     */
    private $lang;

    /**
     *
     * @var string
     */
    private $faculty;

    /**
     *
     * @var string
     */
    private $program;

    /**
     *
     * @var int
     */
    private $generate_answer;

    /**
     *
     * @var int
     */
    private $usermodified;

    /**
     *
     * @var int
     */
    private $timecreated;

    /**
     *
     * @var string
     */
    private $timecreated_hr;

    /**
     *
     * @var int
     */
    private $timemodified;

    /**
     *
     * @var string
     */
    private $timemodified_hr;

    /**
     *
     * @var string
     */
    private $table;


    /**
     *
     *
     */
    public function __construct($id = 0)
    {
        global $CFG, $DB, $DB;

        $this->table = 'local_cria_question';

        parent::set_table($this->table);

        if ($id) {
            $this->id = $id;
            parent::set_id($this->id);
            $result = $this->get_record($this->table, $this->id);
        } else {
            $result = new \stdClass();
            $this->id = 0;
            parent::set_id($this->id);
        }

        $this->intent_id = $result->intent_id ?? 0;
        $this->parent_id = $result->parent_id ?? 0;
        $this->document_name = $result->document_name ?? '';
        $this->name = $result->name ?? '';
        $this->value = $result->value ?? '';
        $this->answer = $result->answer ?? '';
        $this->keywords = $result->keywords ?? '';
        $this->published = $result->published ?? 0;
        $this->lang = $result->lang ?? '';
        $this->faculty = $result->faculty ?? '';
        $this->program = $result->program ?? '';
        $this->generate_answer = $result->generate_answer ?? 0;
        $this->usermodified = $result->usermodified ?? 0;
        $this->timecreated = $result->timecreated ?? 0;
        $this->timecreated_hr = '';
        if ($this->timecreated) {
            $this->timecreated_hr = base::strftime(get_string('strftimedate'), $result->timecreated);
        }
        $this->timemodified = $result->timemodified ?? 0;
        $this->timemodified_hr = '';
        if ($this->timemodified) {
            $this->timemodified_hr = base::strftime(get_string('strftimedate'), $result->timemodified);
        }
    }

    /**
     * @return id - bigint (19)
     */
    public function get_id(): int
    {
        return $this->id;
    }

    /**
     * @return intent_id - bigint (19)
     */
    public function get_intent_id(): int
    {
        return $this->intent_id;
    }

    /**
     * @return parent_id - bigint (19)
     */
    public function get_parent_id(): int
    {
        return $this->parent_id;
    }

    /**
     * @return document_name - varchar (255)
     */
    public function get_document_name(): string
    {
        return $this->document_name;
    }

    /**
     * @return name - varchar (255)
     */
    public function get_name(): string
    {
        return $this->name;
    }

    /**
     * @return value - longtext (-1)
     */
    public function get_value(): string
    {
        return $this->value;
    }

    /**
     * @return answer - longtext (-1)
     */
    public function get_answer(): string
    {
        return $this->answer;
    }

    /**
     * @return keywords - longtext (-1)
     */
    public function get_keywords(): string
    {
        return $this->keywords;
    }

    /**
     * @return published - tinyint (2)
     */
    public function get_published(): int
    {
        return $this->published;
    }

    /**
     * @return lang - varchar (10)
     */
    public function get_lang(): string
    {
        return $this->lang;
    }

    /**
     * @return faculty - varchar (50)
     */
    public function get_faculty(): string
    {
        return $this->faculty;
    }

    /**
     * @return program - varchar (255)
     */
    public function get_program(): string
    {
        return $this->program;
    }

    /**
     * @return generate_answer - tinyint (2)
     */
    public function get_generate_answer(): int
    {
        return $this->generate_answer;
    }

    /**
     * @return usermodified - bigint (19)
     */
    public function get_usermodified(): int
    {
        return $this->usermodified;
    }

    /**
     * @return timecreated - bigint (19)
     */
    public function get_timecreated(): int
    {
        return $this->timecreated;
    }

    /**
     * @return timemodified - bigint (19)
     */
    public function get_timemodified(): int
    {
        return $this->timemodified;
    }

    /**
     * Create example questions
     * @param $question_id
     * @return void
     * @throws \dml_exception
     */
    public function generate_example_questions($intent_id, $question_id)
    {
        global $DB, $USER;
        $INTENT = new intent($intent_id);
        $BOT = new bot($INTENT->get_bot_id());
        $params = json_decode($BOT->get_bot_parameters_json());

        $question = $DB->get_record('local_cria_question', ['id' => $question_id]);
        $system_message = 'You are a bot that writes example questions based on a question provided';
        $prompt = '[question]' . $question->value . '[/question]';
        $prompt .= '[instructions]Write 5 rephrased example questions based on the content in [question]. ' .
            'Return each question in the following JSON format. [{"question": your answer},{"question": your answer}][/instructions]';


        $results = criadex::query($params->llm_model_id, $system_message, $prompt, 1024);
        // Add to logs
        $INTENT->insert_log_record($results, $prompt);

        $messages = json_decode($results->agent_response->chat_response->message->content);
        foreach ($messages as $example) {
            $data = new \stdClass();
            $data->questionid = $question->id;
            $data->value = $example->question;
            $data->usermodified = $USER->id;
            $data->timemodified = time();
            $data->timecreated = time();
            $DB->insert_record('local_cria_question_example', $data);
        }
    }

    /**
     * Translate question
     * @param $intent_id
     * @param $question_text
     * @param $language
     * @return mixed
     * @throws \dml_exception
     */
    public function translate_question($intent_id, $question_text, $language)
    {
        global $DB, $USER;
        $INTENT = new intent($intent_id);
        $BOT = new bot($INTENT->get_bot_id());
        $params = json_decode($BOT->get_bot_parameters_json());
        $system_message = 'You are a bot that translates questions';
        $prompt = '[question]' . $question_text . '[/question]';
        $prompt .= '[instructions]Translate content in [question] to  ' . $language . ' Only return the transalted question, nothing else.[/instructions]';
        $results = criadex::query($params->llm_model_id, $system_message, $prompt, 1024);
        // Add to logs
        $INTENT->insert_log_record($results, $prompt);

        return $results->response->message->content;
    }




    /**
     * @param Type: bigint (19)
     */
    public function set_id($id): void
    {
        $this->id = $id;
    }

    /**
     * @param Type: bigint (19)
     */
    public function set_intent_id($intent_id): void
    {
        $this->intent_id = $intent_id;
    }

    /**
     * @param Type: bigint (19)
     */
    public function set_parent_id($parent_id): void
    {
        $this->parent_id = $parent_id;
    }

    /**
     * @param Type: varchar (255)
     */
    public function set_document_name($document_name): void
    {
        $this->document_name = $document_name;
    }

    /**
     * @param Type: longtext (-1)
     */
    public function set_value($value): void
    {
        $this->value = $value;
    }

    /**
     * @param Type: longtext (-1)
     */
    public function set_answer($answer): void
    {
        $this->answer = $answer;
    }

    /**
     * @param Type: longtext (-1)
     */
    public function set_keywords($keywords): void
    {
        $this->keywords = $keywords;
    }

    /**
     * @param Type: tinyint (2)
     */
    public function set_published($published): void
    {
        $this->published = $published;
    }

    /**
     * @param Type: varchar (10)
     */
    public function set_lang($lang): void
    {
        $this->lang = $lang;
    }

    /**
     * @param Type: varchar (50)
     */
    public function set_faculty($faculty): void
    {
        $this->faculty = $faculty;
    }

    /**
     * @param Type: varchar (255)
     */
    public function set_program($program): void
    {
        $this->program = $program;
    }

    /**
     * @param Type: tinyint (2)
     */
    public function set_generate_answer($generate_answer): void
    {
        $this->generate_answer = $generate_answer;
    }

    /**
     * @param Type: bigint (19)
     */
    public function set_usermodified($usermodified): void
    {
        $this->usermodified = $usermodified;
    }

    /**
     * @param Type: bigint (19)
     */
    public function set_timecreated($timecreated): void
    {
        $this->timecreated = $timecreated;
    }

    /**
     * @param Type: bigint (19)
     */
    public function set_timemodified($timemodified): void
    {
        $this->timemodified = $timemodified;
    }

    public function delete_record(): bool
    {
        global $DB;
        // Get the question based on id
        $INTENT = new intent($this->intent_id);
        // Delete question from criabot
        $delete_on_bot_server = criabot::question_delete($INTENT->get_bot_name(), $this->document_name);
        print_object('Delete status');
        print_object($delete_on_bot_server);
        if ($delete_on_bot_server->status == 200) {
            // Delete example questions
            $DB->delete_records('local_cria_question_example', ['questionid' => $this->id]);
            // Delete question from database
            return parent::delete_record(); // TODO: Change the autogenerated stub
        } else {
            return false;
        }
    }

    /**
     * @return \stdClass
     * @throws \dml_exception
     */
    public function publish() {
        $INTENT = new intent($this->intent_id);
        $result = $INTENT->publish_question($this->id);
        $status = new \stdClass();
        if ($result) {
            $status->status = 200;
            $status->message = "Question succesfully published.";
            return $status;
        } else {
            $status->status = 404;
            $status->message = "Errpr: Question was not published.";
            return $status;
        }

    }

}