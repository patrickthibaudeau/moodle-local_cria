<?php
/*
 * Author: Admin User
 * Create Date: 27-07-2023
 * License: LGPL 
 * 
 */
namespace local_cria;

use core\notification;
use local_cria\crud;
use local_cria\criabot;
use local_cria\criabdex;

class intent extends crud
{


    /**
     *
     * @var int
     */
    private $id;

    /**
     *
     * @var string
     */
    private $name;

    /**
     *
     * @var string
     */
    private $description;

    /**
     *
     * @var int
     */
    private $bot_id;

    /**
     *
     * @var int
     */
    private $is_default;

    /**
     *
     * @var int
     */
    private $published;

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
     * @var int
     */
    private $timemodified;

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

        $this->table = 'local_cria_intents';
        parent::set_table($this->table);

        if ($id) {
            $this->id = $id;
            parent::set_id($this->id);
            $result = $this->get_record($this->table, ['id' => $this->id]);
        } else {
            $result = new \stdClass();
            $this->id = 0;
            parent::set_id($this->id);
        }

        $this->name = $result->name ?? '';
        $this->description = $result->description ?? '';
        $this->bot_id = $result->bot_id ?? 0;
        $this->is_default = $result->is_default ?? 0;
        $this->published = $result->published ?? 0;
        $this->usermodified = $result->usermodified ?? 0;
        $this->timecreated = $result->timecreated ?? 0;
        $this->timemodified = $result->timemodified ?? 0;
    }

    /**
     * @param $data
     * @return int
     * @throws \dml_exception
     */
    public function insert_record($data): int
    {
        global $DB;
        $new_intent_id  = parent::insert_record($data); // TODO: Change the autogenerated stub
        if ($data->published) {
            $NEW_INTENT = new intent($new_intent_id);
            $result = $NEW_INTENT->create_intent_on_bot_server();
            // Update intent bot_api_key
            $params = new \stdClass();
            $params->id = $new_intent_id;
            $params->bot_api_key = $result->bot_api_key;
            $DB->update_record('local_cria_intents', $params);
        }
        return $new_intent_id;
    }

    public function update_record($data): int
    {
        parent::update_record($data); // TODO: Change the autogenerated stub
        if ($data->published) {
            $this->update_intent_on_bot_server();
        }

        return $this->id;
    }

    public function create_example_questions($question_id)
    {
        global $DB, $USER;

        $BOT = new bot($this->bot_id);
        $params = json_decode($BOT->get_bot_parameters_json());
        $question = $DB->get_record('local_cria_question', ['id' => $question_id]);
        $system_message = 'You are a bot that writes example questions based on a question provided';
        $prompt = '[question]' . $question->value . '[/question]';
        $prompt .= '[instructions]Write 5 rephrased example questions based on the content in [question]. ' .
            'Return each question in the following JSON format. [{"question": your answer},{"question": your answer}][/instructions]';
        $results = criadex::query($params->llm_model_id,$system_message,$prompt, 1024);
        $messages = json_decode($results->response->message->content);
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
     * Return the id of the intent
     */
    public function get_id(): int
    {
        return $this->id;
    }

    /*
     *  Return the name of the intent
     */
    public function get_name(): string
    {
        return $this->name;
    }

    /**
     * Return the description of the intent
     */
    public function get_description(): string
    {
        return $this->description;
    }

    /**
     * @return Int bot_id
     */
    public function get_bot_id(): int
    {
        return $this->bot_id;
    }

    /**
     * @return int default
     */
    public function get_is_default(): int
    {
        return $this->is_default;
    }

    /**
     * @return int publish
     */
    public function get_published(): int
    {
        return $this->published;
    }


    /**
     *  Intent always uses bot parameters.
     *  This function returns the bot parameters in json format
     * @return String
     */
    public function get_bot_parameters_json(): string
    {
        $BOT = new \local_cria\bot($this->bot_id);

        return $BOT->get_bot_parameters_json();
    }

    /**
     * @return array|false
     * @throws \dml_exception
     */
    public function get_questions(): mixed
    {
        global $DB;
        if ($questions = $DB->get_records('local_cria_question', ['intentid' => $this->id], 'id')) {
            return array_values($questions);
        }
        return false;
    }

    /**
     * Return user id
     */
    public function get_usermodified(): int
    {
        return $this->usermodified;
    }

    /**
     * Return time created
     */
    public function get_timecreated(): int
    {
        return $this->timecreated;
    }

    /**
     * Return time modified
     */
    public function get_timemodified(): int
    {
        return $this->timemodified;
    }

    /**
     * @param \local_cria\intent: bigint (18)
     */
    public function set_id($id): void
    {
        $this->id = $id;
    }

    /**
     * @param \local_cria\intent: varchar (255)
     */
    public function set_name($name): void
    {
        $this->name = $name;
    }

    /**
     * @param \local_cria\intent: longtext (-1)
     */
    public function set_description($description): void
    {
        $this->description = $description;
    }

    /**
     * @param $public
     * @return void
     */
    public function set_pulish($publish): void
    {
        $this->publish = $publish;
    }


    /**
     * @return void
     * @throws \coding_exception
     * @throws \dml_exception
     */
    public function create_intent_on_bot_server()
    {
        $bot_name = $this->bot_id . '-' . $this->id;
        return criabot::bot_create((string)$bot_name, $this->get_bot_parameters_json());
    }

    /**
     * @return void
     * @throws \coding_exception
     * @throws \dml_exception
     */
    public function update_intent_on_bot_server()
    {
        $bot_name = $this->bot_id . '-' . $this->id;
        $bot_exists = criabot::bot_about((string)$bot_name);

        if ($bot_exists->status == 404) {
            $result = criabot::bot_create((string)$bot_name, $this->get_bot_parameters_json());
        } else {
            $result = criabot::bot_update((string)$bot_name, $this->get_bot_parameters_json());
        }
        if ($result->status == 200) {
            return true;
        } else {
            \core\notification::error(
                'STATUS: ' . $result->status . ' CODE: ' . $result->code . ' Message: ' .  $result->message
            );
        }
    }

}