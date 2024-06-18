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
 * Create Date: 27-07-2023
 * License: LGPL 
 * 
 */

namespace local_cria;

use core\notification;
use local_cria\crud;
use local_cria\criabot;
use local_cria\criabdex;
use local_cria\intents;
use local_cria\intent;

class bot extends crud
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
    private $bot_type;

    /**
     *
     * @var int
     */
    private $publish;

    /**
     * @var string
     */
    private $email;

    /**
     *
     * @var int
     */
    private $requires_user_prompt;

    /**
     *
     * @var int
     */
    private $requires_content_prompt;

    /**
     *
     * @var int
     */
    private $has_user_prompt;

    /**
     *
     * @var string
     */
    private $user_prompt;
    /**
     *
     * @var int
     */
    private $model_id;

    /**
     *
     * @var int
     */
    private $embedding_id;

    /**
     *
     * @var string
     */
    private $bot_api_key;

    /**
     *
     * @var int
     */
    private $system_reserved;

    /**
     *
     * @var string
     */
    private $plugin_path;

    /**
     *
     * @var string
     */
    private $bot_system_message;

    /**
     *
     * @var int
     */
    private $botwatermark;

    /**
     *
     * @var int
     */
    private $embed_enabled;

    /**
     *
     * @var int
     */
    private $embed_postion;

    /**
     *
     * @var string
     */
    private $theme_color;

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
     * @var string
     */
    private $welcome_message;

    /**
     *
     * @var int
     */
    private $max_tokens;

    /**
     *
     * @var float
     */
    private $temperature;

    /**
     *
     * @var float
     */
    private $top_p;

    /**
     *
     * @var float
     */
    private $top_k;

    /**
     *
     * @var float
     */
    private $minimum_relevance;

    /**
     *
     * @var int
     */
    private $max_context;

    /**
     *
     * @var string
     */
    private $no_context_message;

    /**
     * Child bots are stored as a JSON array
     * @var string
     */
    private $child_bots;

    /**
     *
     * @var int
     */
    private $available_child;

    /**
     *
     * @var string
     */
    private $tone;

    /**
     * @var string
     */
    private $response_length;

    /**
     * @var int
     */
    private $fine_tuning;

    /**
     * @var int
     */
    private $no_context_use_message;

    /**
     * @var int
     */
    private $no_context_llm_guess;

    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $subtitle;

    /**
     * @var int
     */
    private $top_n;

    /**
     * @var float
     */
    private $min_k;

    /**
     * @var int
     */
    private $rerank_model_id;

    /**
     * @var string
     */
    private $bot_locale;

    /**
     * @var string
     */
    private $parse_strategy;


    /**
     *
     *
     */
    public function __construct($id = 0)
    {
        global $CFG, $DB, $DB;

        $this->table = 'local_cria_bot';

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

        $this->name = $result->name ?? '';
        $this->description = $result->description ?? '';
        $this->bot_type = $result->bot_type ?? 0;
        $this->system_reserved = $result->system_reserved ?? 0;
        $this->parse_strategy = $result->parse_strategy ?? '';
        $this->plugin_path = $result->plugin_path ?? '';
        $this->model_id = $result->model_id ?? 0;
        $this->publish = $result->publish ?? 0;
        $this->has_user_prompt = $result->has_user_prompt ?? 0;
        $this->requires_content_prompt = $result->requires_content_prompt ?? 0;
        $this->requires_user_prompt = $result->requires_user_prompt ?? 0;
        $this->user_prompt = $result->user_prompt ?? '';
        $this->embedding_id = $result->embedding_id ?? 0;
        $this->email = $result->email ?? '';
        $this->bot_system_message = $result->bot_system_message ?? '';
        $this->welcome_message = $result->welcome_message ?? '';
        $this->theme_color = $result->theme_color ?? '';
        $this->usermodified = $result->usermodified ?? 0;
        $this->timecreated = $result->timecreated ?? 0;
        $this->timemodified = $result->timemodified ?? 0;
        $this->max_tokens = $result->max_tokens ?? 0;
        $this->temperature = $result->temperature ?? 0;
        $this->top_p = $result->top_p ?? 0;
        $this->top_k = $result->top_k ?? 0;
        $this->top_n = $result->top_n ?? 0;
        $this->min_k = $result->min_k ?? 0;
        $this->rerank_model_id = $result->rerank_model_id ?? 0;
        $this->min_relevance = $result->min_relevance ?? 0;
        $this->max_context = $result->max_context ?? 0;
        $this->no_context_message = $result->no_context_message ?? '';
        $this->no_context_use_message = $result->no_context_use_message ?? 0;
        $this->no_context_llm_guess = $result->no_context_llm_guess ?? 0;
        $this->embed_enabled = $result->embed_enabled ?? 0;
        $this->botwatermark = $result->botwatermark ?? 0;
        $this->embed_postion = $result->embed_postion ?? 2;
        $this->child_bots = $result->child_bots ?? '';
        $this->available_child = $result->available_child ?? 0;
        $this->fine_tuning = $result->fine_tuning ?? 0;
        $this->tone = $result->tone ?? '';
        $this->response_length = $result->response_length ?? '';
        $this->title = $result->title ?? '';
        $this->subtitle = $result->subtitle ?? '';
        $this->bot_locale = $result->bot_locale ?? 'en-US';
    }

    /**
     * @return id - int
     */
    public function get_id(): int
    {
        return $this->id;
    }

    /**
     * @return name - varchar (255)
     */
    public function get_name(): string
    {
        return $this->name;
    }

    /**
     * @return description - longtext (-1)
     */
    public function get_description(): string
    {
        return $this->description;
    }

    /**
     * @return bot_type - tinyint (2)
     */
    public function get_bot_type(): int
    {
        return $this->bot_type;
    }

    /**
     * @return email - varchar (255)
     */
    public function get_email(): string
    {
        return $this->email;
    }

    /**
     * @return int
     */
    public function get_publish(): int
    {
        return $this->publish;
    }

    /**
     * @return string
     */
    public function get_parse_strategy(): string
    {
        return $this->parse_strategy;
    }

    /**
     * @return string
     */
    public function get_bot_api_key(): string
    {
        global $DB;
        // Get default intent for this bot
        $default_intent = $DB->get_record('local_cria_intents', ['bot_id' => $this->id, 'is_default' => 1]);
        return $default_intent->bot_api_key ?? '';
    }

    /**
     * @return int
     */
    public function get_has_user_prompt(): int
    {
        return $this->has_user_prompt;
    }

    /**
     * @return int
     */
    public function get_requires_content_prompt(): int
    {
        return $this->requires_content_prompt;
    }

    /**
     * @return int
     */
    public function get_requires_user_prompt(): int
    {
        return $this->requires_user_prompt;
    }

    /**
     * @return int
     */
    public function get_model_id(): int
    {
        return $this->model_id;
    }

    /**
     * @return int
     */
    public function get_embedding_id(): int
    {
        return $this->embedding_id;
    }

    /**
     * @return string
     */
    public function get_user_prompt(): string
    {
        return $this->user_prompt;
    }

    /**
     * @return system_reserved - int (1)
     */
    public function get_system_reserved(): int
    {
        return $this->system_reserved;
    }

    /**
     * @return system_reserved - string (255)
     */
    public function get_plugin_path(): string
    {
        return $this->plugin_path;
    }

    /**
     * @return int
     */
    public function get_max_tokens(): int
    {
        return $this->max_tokens;
    }

    public function get_model_max_tokens(): int
    {
        $MODEL = new \local_cria\model($this->model_id);
        return $MODEL->get_max_tokens();
    }

    /**
     * @return float
     */
    public function get_temperature(): float
    {
        return $this->temperature;
    }

    /**
     * @return float
     */
    public function get_top_p(): float
    {
        return $this->top_p;
    }

    /**
     * @return float
     */
    public function get_top_k(): float
    {
        return $this->top_k;
    }

    /**
     * @return int
     */
    public function get_top_n(): int
    {
        return $this->top_n;
    }

    /**
     * @return float
     */
    public function get_min_k(): float
    {
        return $this->min_k;
    }

    /**
     * @return int
     */
    public function get_rerank_model_id(): int
    {
        return $this->rerank_model_id;
    }

    /**
     * @return float
     */
    public function get_min_n(): float
    {
        return $this->min_relevance;
    }

    /**
     * @return int
     */
    public function get_max_context(): int
    {
        return $this->max_context;
    }

    /**
     * @return string
     */
    public function get_no_context_message(): string
    {
        return $this->no_context_message;
    }

    /**
     * @return int
     */
    public function get_no_context_use_message(): int
    {
        return $this->no_context_use_message;
    }


    /**
     * @return int
     */
    public function get_no_context_llm_guess(): int
    {
        return $this->no_context_llm_guess;
    }

    /**
     * @return string
     */
    public function get_tone(): string
    {
        return $this->tone;
    }

    /**
     * @return string
     */
    public function get_response_length(): string
    {
        return $this->response_length;
    }

    /**
     * @return string
     */
    public function get_child_bots(): string
    {
        return $this->child_bots;
    }

    /**
     * @return int
     */
    public function get_available_child(): int
    {
        return $this->available_child;
    }

    /**
     * @return int
     */
    public function get_fine_tuning(): int
    {
        return $this->fine_tuning;
    }

    /**
     * Return child_bots as an array
     * @return array
     */
    public function get_child_bots_array(): array
    {
        return json_decode($this->child_bots);
    }

    /**
     * @return string
     */
    public function get_title(): string
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function get_subtitle(): string
    {
        return $this->subtitle;
    }

    /**
     * @return int
     */
    public function get_embed_enabled(): int
    {
        return $this->embed_enabled;
    }

    /**
     * @return bool
     */
    public function get_embed_enabled_bool()
    {
        if ($this->embed_enabled == 1) {
            return true;
        } else {
            return null;
        }
    }

    /**
     * @return bool
     */
    public function get_bot_watermark(): int
    {
        if ($this->botwatermark == 1) {
            return true;
        } else {
            return false;
        }
    }

    public function get_bot_watermark_string_bool(): string
    {
        if ($this->botwatermark == 1) {
            return 'true';
        } else {
            return 'false';
        }
    }

    /**
     * @return string
     */
    public function get_embed_position(): int
    {
        return $this->embed_postion;
    }

    /**
     * @return string
     */
    public function get_bot_locale(): string
    {
        return $this->bot_locale;
    }

    /**
     * @return string
     */
    public function get_icon_url(): string
    {
        $fs = get_file_storage();
        $contextid = \context_system::instance()->id;
// Returns an array of `stored_file` instances.
        $files = $fs->get_area_files($contextid, 'local_cria', 'bot_icon', $this->id);
        foreach ($files as $file) {
            if ($file->get_filename() != '.' && $file->get_filename() != '') {
                $moodle_url = \moodle_url::make_pluginfile_url(
                    $file->get_contextid(),
                    $file->get_component(),
                    $file->get_filearea(),
                    $file->get_itemid(),
                    $file->get_filepath(),
                    $file->get_filename()
                );
                return $moodle_url;
            }
        }
        return '';
    }

    /**
     *  Return paramaters for the bot
     * These parameters are used to create the bot on the bot server
     * @return String
     */
    public function get_bot_parameters_json(): string
    {
        $MODEL = new \local_cria\model($this->model_id);
        $EMBEDDING_MODEL = new \local_cria\model($this->embedding_id);
        $RERANK_MODEL = new \local_cria\model($this->rerank_model_id);
        $params = '{' .
            '"max_reply_tokens": ' . $this->get_max_tokens() . ',' .
            '"temperature": ' . $this->get_temperature() . ',' .
            '"top_p": ' . $this->get_top_p() . ',' .
            '"top_k": ' . $this->get_top_k() . ',' .
            '"top_n": ' . $this->get_top_n() . ',' .
            '"min_n": ' . $this->get_min_n() . ',' .
            '"min_k": ' . $this->get_min_k() . ',' .
            '"max_input_tokens": ' . $this->get_max_context() . ',' .
            '"no_context_message": "' . str_replace('"', '\"', $this->get_no_context_message()) . '",' .
            '"no_context_use_message": ' . $this->get_no_context_use_message() . ',' .
            '"no_context_llm_guess": ' . $this->get_no_context_llm_guess() . ',' .
            '"system_message": "' . str_replace('"', '\"', $this->get_bot_system_message()) . '",' .
            '"llm_model_id": ' . $MODEL->get_criadex_model_id() . ',' .
            '"embedding_model_id": ' . $EMBEDDING_MODEL->get_criadex_model_id() . ',' .
            '"rerank_model_id": ' . $RERANK_MODEL->get_criadex_model_id() .
            '}';
        return $params;
    }

    /**
     * @return int
     * @throws \dml_exception
     */
    public function get_has_auto_test_questions()
    {
        global $DB;
        return $DB->count_records('local_cria_qa', ['bot_id' => $this->id]);
    }

    /**
     * Insert record into selected table
     * @param object $data
     * @global \stdClass $USER
     * @global \moodle_database $DB
     */
    public function insert_record($data): int
    {
        global $DB, $USER;

        if (isset($data->child_bots)) {
            $data->child_bots = json_encode($data->child_bots);
        }

        if (!isset($data->timecreated)) {
            $data->timecreated = time();
        }

        if (!isset($data->imemodified)) {
            $data->timemodified = time();
        }
        //Set user
        $data->usermodified = $USER->id;

        $id = $DB->insert_record($this->table, $data);

        $NEW_BOT = new bot($id);
        // Create intents for this bot
        // Only if bot uses bot server
        // Otherwise, create bot on bot server
        if ($NEW_BOT->use_bot_server()) {
            $intent_id = $this->create_default_intent($id);
            // Create embed server code
            criaembed::manage_insert($intent_id);
        } else {
            $NEW_BOT->create_bot_on_bot_server(0);
        }
        return $id;
    }

    /**
     * @param $data
     * @return int
     * @throws \coding_exception
     * @throws \dml_exception
     */
    public function update_record($data): int
    {
        //Convert child_bot array to json
        if (isset($data->child_bots)) {
            $data->child_bots = json_encode($data->child_bots);
        }

        parent::update_record($data); // TODO: Change the autogenerated stub
        // If this bot uses the bot server, update the bot on the bot server
        if ($this->use_bot_server()) {
            $default_intent = $this->create_default_intent($this->id);
            // Update embed server code
            $embed_bot = criaembed::manage_get_config($this->get_default_intent_id());
            // If embed doesn't exist then create it

            if ($embed_bot->status != 200) {
                criaembed::manage_insert($this->get_default_intent_id());
            } else {
                // Update embed
                criaembed::manage_update($this->get_default_intent_id());
            }

            return $default_intent;
        }

        return true;
    }

    /**
     * @param $bot_id
     * @return int|true|null
     * @throws \coding_exception
     * @throws \dml_exception
     */
    protected function create_default_intent($bot_id)
    {
        $INTENT = new intent();
        // If default intent does not exist, create it
        if (!$INTENT->default_intent_exists($bot_id)) {
            $params = new \stdClass();
            $params->bot_id = $bot_id;
            $params->is_default = 1;
            $params->name = 'General';
            $params->description = 'General intent for bot.';
            $params->published = 1;
            // Insert record. Bot will be created automatically on bot server.
            $intent_id = $INTENT->insert_record($params);
            return $intent_id;
        } else {
            // Update the bot on bot server
            $BOT = new bot($bot_id);
            $update_bot = $BOT->update_bot_on_bot_server($BOT->get_default_intent_id());
            return $update_bot;
        }
    }

    /**
     * Get Model record
     * @return mixed|\stdClass
     */
    public function get_model_config(): \stdClass
    {
        $MODEL = new model($this->model_id);
        return $MODEL->get_result();
    }

    /**
     * Get Embedding record
     * @return mixed|\stdClass
     */
    public function get_embedding_config(): \stdClass
    {
        $MODEL = new model($this->embedding_id);
        return $MODEL->get_result();
    }

    /**
     * @return string
     * @throws \dml_exception
     */
    public function get_bot_type_system_message(): string
    {
        global $DB;
        $bot_type = $DB->get_record('local_cria_type', array('id' => $this->bot_type));
        return $bot_type->system_message ?? '';
    }

    /**
     * @return string
     * @throws \dml_exception
     */
    public function use_bot_server(): string
    {
        global $DB;
        $bot_type = $DB->get_record('local_cria_type', array('id' => $this->bot_type));
        return $bot_type->use_bot_server;
    }

    /**
     * Get bot name based on whether or not this bot uses the bot server
     */
    public function get_bot_name(): string
    {
        if ($this->use_bot_server()) {
            return $this->id . '-' . $this->get_default_intent_id();
        } else {
            return $this->id;
        }
    }

    /**
     * @return bot_system_message - longtext (-1)
     */
    public function get_bot_system_message(): string
    {
        return $this->bot_system_message;
    }

    /**
     * Builds the system message based on the bot type and the local bot system message
     * @return string
     * @throws \dml_exception
     */
    public function concatenate_system_messages(): string
    {
        return $this->get_bot_type_system_message() . $this->get_bot_system_message();
    }

    /**
     * Returns the number of intents for this bot
     * @return int
     * @throws \dml_exception
     */
    public function get_number_of_intents(): int
    {
        global $DB;
        $intents = $DB->get_records('local_cria_intents', ['bot_id' => $this->id]);
        return count($intents);
    }

    /**
     * Returns an array of intents with name and description
     * @return array
     * @throws \dml_exception
     */
    public function get_intents(): array
    {
        global $DB;
        $intents = [];
        // Start with intents for this bot
        $intent_records = $DB->get_records('local_cria_intents', ['bot_id' => $this->id]);
        $i = 0;
        foreach ($intent_records as $intent_record) {
            $intents[$i]['name'] = $this->id . '-' . $intent_record->id;
            $intents[$i]['description'] = $intent_record->description;
            $i++;
        }
        // Get all child bots array
        // Add all intents into the array
        $child_bots = json_decode($this->child_bots);
        if (!empty($child_bots)) {
            foreach ($child_bots as $child_bot_id) {
                $intent_records = $DB->get_records('local_cria_intents', ['bot_id' => $child_bot_id]);
                foreach ($intent_records as $intent_record) {
                    $intents[$i]['name'] = $child_bot_id . '-' . $intent_record->id;
                    $intents[$i]['description'] = $intent_record->description;
                    $i++;
                }
            }
        }
        return $intents;
    }


    /**
     * @return string
     */
    public function get_welcome_message(): string
    {
        return $this->welcome_message;
    }

    /**
     * @return string
     */
    public function get_theme_color(): string
    {
        return $this->theme_color;
    }

    /**
     * @return usermodified - bigint (18)
     */
    public function get_usermodified(): int
    {
        return $this->usermodified;
    }

    /**
     * @return timecreated - bigint (18)
     */
    public function get_timecreated(): int
    {
        return $this->timecreated;
    }

    /**
     * @return timemodified - bigint (18)
     */
    public function get_timemodified(): int
    {
        return $this->timemodified;
    }

    /**
     * @param Type: bigint (18)
     */
    public function set_id($id): void
    {
        $this->id = $id;
    }

    /**
     * @param Type: varchar (255)
     */
    public function set_name($name): void
    {
        $this->name = $name;
    }

    /**
     * @param Type: longtext (-1)
     */
    public function set_description($description): void
    {
        $this->description = $description;
    }

    /**
     * @param Type: tinyint (2)
     */
    public function set_bot_type($bot_type): void
    {
        $this->bot_type = $bot_type;
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
     * @param $requires_user_prompt
     * @return void
     */
    public function set_requires_user_prompt($requires_user_prompt): void
    {
        $this->requires_user_prompt = $requires_user_prompt;
    }

    /**
     * @param $user_prompt
     * @return void
     */
    public function set_user_prompt($user_prompt): void
    {
        $this->user_prompt = $user_prompt;
    }

    /**
     * @param Type: longtext (-1)
     */
    public function set_bot_system_message($bot_system_message): void
    {
        $this->bot_system_message = $bot_system_message;
    }

    /**
     * @param Type: bigint (18)
     */
    public function set_usermodified($usermodified): void
    {
        $this->usermodified = $usermodified;
    }

    /**
     * @param Type: bigint (18)
     */
    public function set_timecreated($timecreated): void
    {
        $this->timecreated = $timecreated;
    }

    /**
     * @param Type: bigint (18)
     */
    public function set_timemodified($timemodified): void
    {
        $this->timemodified = $timemodified;
    }

    /**
     * Get default intent id for this bot
     * @return int
     */
    public function get_default_intent_id(): int
    {
        global $DB;
        $intent = $DB->get_record('local_cria_intents', ['bot_id' => $this->id, 'is_default' => 1], 'id');
        return $intent->id ?? 0;
    }

    public function get_all_intents(): array
    {
        global $DB;
        return $DB->get_records('local_cria_intents', ['bot_id' => $this->id]);
    }

    /**
     * @param $intent_id int
     * @return void
     * @throws \dml_exception
     */
    public function create_bot_on_bot_server($intent_id = 0)
    {
        // Bot name based on whether an intent id is passed
        if ($intent_id == 0) {
            $bot_name = $this->id;
        } else {
            $bot_name = $this->id . '-' . $intent_id;
        }
        // Bot names are fomratted as bot_id-intent_id
        return criabot::bot_create($bot_name, $this->get_bot_parameters_json());
    }

    /**
     * @return void
     * @throws \coding_exception
     * @throws \dml_exception
     */
    public function update_bot_on_bot_server($intent_id = 0)
    {
        // Bot name based on whether an intent id is passed
        if ($intent_id == 0) {
            $bot_name = $this->id;
        } else {
            $bot_name = $this->id . '-' . $intent_id;
        }
        $bot_exists = criabot::bot_about($bot_name);
        $update = false;
        if ($bot_exists->status == 404 && $intent_id == 0) {
            //Create intent
            $INTENT = new intent();
            $data = new \stdClass();
            $data->bot_id = $this->id;
            $data->is_default = 1;
            $data->name = 'General';
            $data->description = 'General intent for bot.';
            $data->published = 1;
            $intent_id = $INTENT->insert_record($data);
        } elseif ($bot_exists->status == 404 && $intent_id != 0) {
            // Create bot
            $result = $this->create_bot_on_bot_server($intent_id);
        } else {
            $result = criabot::bot_update($bot_name, $this->get_bot_parameters_json());
            $update = true;
        }
        if ($result->status == 200) {
            // If an update was performed, and there is an intent, update all intents for this bot.
            if ($update && $intent_id != 0) {
                $INTENTS = new intents($this->id);
                foreach ($INTENTS->get_records() as $intent) {
                    $INTENT = new intent($intent->id);
                    if ($INTENT->get_published() && $INTENT->get_is_default() == 0) {
                        $INTENT->update_intent_on_bot_server();
                    }
                }
            }
            return true;
        } else {
            \core\notification::error(
                'STATUS: ' . $result->status . ' CODE: ' . $result->code . ' Message: ' . $result->message
            );
        }
    }

    /**
     * @return array
     * @throws \coding_exception
     * @throws \dml_exception
     */
    public function get_available_keywords(): array
    {
        $ENTITIES = new entities($this->id);
        $keywords = [];
        foreach ($ENTITIES->get_records() as $entity) {
            $KEYWORDS = new keywords($entity->id);
            $entity_keywords = $KEYWORDS->get_records();
            $options = [];
            foreach ($entity_keywords as $ek) {
                $options[$ek->id] = $ek->value;
            }
            $keywords[$entity->name] = $options;

            unset($KEYWORDS);

        }
        unset($ENTITIES);

        return $keywords;
    }

    /**
     * @return bool
     * @throws \coding_exception
     * @throws \dml_exception
     */
    public function delete_record(): bool
    {
        global $DB;
        // Does this bot use indexing?
        if ($this->use_bot_server()) {
            // How many intents does this bot have?
            $intents = $this->get_all_intents();
            // Let's get all files for the intent and delete them
            // Once files are deleted, delete the intent/bot
            foreach ($intents as $intent) {
                $INTENT = new intent($intent->id);
                $files = $INTENT->get_files();
                foreach ($files as $file) {
                    $FILE = new file($file->id);
                    $FILE->delete_record();
                    unset($FILE);
                }
                $INTENT->delete_record();
                unset($INTENT);
            }

        }
        return $DB->delete_records($this->table, array('id' => $this->get_id()));
    }

    /**
     * Send an email to the bot owner
     * @param $prompt
     * @return string
     * @throws \dml_exception
     */
    public function send_no_context_email($prompt = '', $answer = '')
    {
        global $DB;
        if (!empty($this->get_email())) {
            // Get emails
            $emails = explode(';', $this->get_email());
            // Get a user object
            $user = $DB->get_record('user', ['id' => 1]);
            // Loop through and send the message
            foreach ($emails as $email) {
                // Replace user object with new values
                $user->id = 99999999999;
                $user->email = $email;
                $user->username = $email;
                $user->firstname = '';
                $user->lastname = '';
                $user->firstnamephonetic = '';
                $user->lastnamephonetic = '';
                $user->middlename = '';
                $user->auth = 'manual';
                $user->language = 'en';
                $user->idnumber = '';
                $user->suspended = 0;
                // Prepare subject and message
                $subject = get_string('no_context_subject', 'local_cria');
                $message = get_string('no_context_email_message', 'local_cria',
                    ['bot_name' => $this->get_name(), 'prompt' => $prompt]
                );
                // IF this bot uses llm guess then add to the message
                if ($this->get_no_context_llm_guess()) {
                    $message .= get_string('no_context_email_message_llm_guess', 'local_cria',
                        ['answer' => strip_tags($answer)]
                    );
                }

                email_to_user($user, null, $subject, $message);
            }
        }
    }

}