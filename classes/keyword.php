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
 * Create Date: 23-12-2023
 * License: LGPL 
 * 
 */

namespace local_cria;

use local_cria\crud;

class keyword extends crud
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
    private $entity_id;

    /**
     *
     * @var string
     */
    private $value;

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
     * @var int
     */
    private $bot_id;


    /**
     *
     *
     */
    public function __construct($id = 0)
    {
        global $CFG, $DB, $DB;

        $this->table = 'local_cria_keyword';

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

        $this->entity_id = $result->entity_id ?? 0;
        $this->value = $result->value ?? '';
        $this->usermodified = $result->usermodified ?? 0;
        $this->timecreated = $result->timecreated ?? 0;
        $this->timecreated_hr = '';
        if ($this->timecreated) {
            $this->timecreated_hr = strftime(get_string('strftimedate'), $result->timecreated);
        }
        $this->timemodified = $result->timemodified ?? 0;
        $this->timemodified_hr = '';
        if ($this->timemodified) {
            $this->timemodified_hr = strftime(get_string('strftimedate'), $result->timemodified);
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
     * @return entity_id - bigint (19)
     */
    public function get_entity_id(): int
    {
        return $this->entity_id;
    }

    /**
     * @return value - varchar (255)
     */
    public function get_value(): string
    {
        return $this->value;
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
     * @param Type: bigint (19)
     */
    public function set_id($id): void
    {
        $this->id = $id;
    }

    /**
     * @param Type: bigint (19)
     */
    public function set_entity_id($entity_id): void
    {
        $this->entity_id = $entity_id;
    }

    /**
     * @param Type: varchar (255)
     */
    public function set_value($value): void
    {
        $this->value = $value;
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

    /**
     * Create example questions
     * @param $question_id
     * @return void
     * @throws \dml_exception
     */
    public function create_synoyms()
    {
        global $DB, $USER;
        $ENTITY = new entity($this->entity_id);
        $BOT = new bot($ENTITY->get_bot_id());
        $params = json_decode($BOT->get_bot_parameters_json());
        $system_message = 'You are a bot that writes synonyms from the keyword provided';
        $prompt = "---\nkeyword:" . $this->value . "\n---\n";
        $prompt .= 'Write up to 5 synonyms of the keyword above. ' .
            'Return each synonym in the following JSON format. [{"synonym": your answer},{"synonym": your answer}';
        $results = criadex::query($params->llm_model_id, $system_message, $prompt, 1024);
        // Add to logs
        $this->insert_log_record($ENTITY->get_bot_id(), $results, $prompt);

        $synonyms = json_decode($results->agent_response->chat_response->message->content);
        foreach ($synonyms as $synonym) {
            $data = new \stdClass();
            $data->keyword_id = $this->id;
            $data->value = $synonym->synonym;
            $data->usermodified = $USER->id;
            $data->timemodified = time();
            $data->timecreated = time();
            $DB->insert_record('local_cria_synonyms', $data);
        }
    }

    /**
     * Insert into logs
     * @param $results
     * @param $prompt
     * @return void
     * @throws \dml_exception
     */
    public function insert_log_record($bot_id, $results, $prompt)
    {
        // Get token usage
        $token_usage = $results->agent_response->chat_response->raw->usage;
        // loop through token usage and add the prompt tokens and completion tokens
        $prompt_tokens = 0;
        $completion_tokens = 0;
        $total_tokens = 0;

        $prompt_tokens = $token_usage->prompt_tokens;
        $completion_tokens = $token_usage->completion_tokens;
        $total_tokens = $token_usage->total_tokens;

        $cost = gpt::_get_cost($bot_id, $prompt_tokens, $completion_tokens);
        // Insert logs
        logs::insert(
            $bot_id,
            $prompt,
            $results->agent_response->chat_response->message->content,
            $prompt_tokens,
            $completion_tokens,
            $total_tokens,
            $cost,
            '');
    }

    /**
     * @return bool
     * @throws \dml_exception
     */
    public function delete_record(): bool
    {
        global $DB;
        $DB->delete_records('local_cria_synonyms', ['keyword_id' => $this->id]);
        parent::delete_record(); // TODO: Change the autogenerated stub
        return true;
    }
}