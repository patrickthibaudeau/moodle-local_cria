<?php

namespace local_cria;

class questions
{

    /**
     * @var int
     */
    private $intent_id;
    /**
     *
     *@global \moodle_database $DB
     */
    public function __construct($intent_id = 0)
    {
        global $DB, $USER;
        $this->intent_id = $intent_id;
    }

    /**
     * Get records
     */
    public function get_records(): array {
        global $DB;
        return $DB->get_records('local_cria_question', array('intent_id' => $this->intent_id), 'timemodified DESC');
    }

    /**
     * @param $intent_id
     * @return array|false
     * @throws \dml_exception
     */
    public function get_questions()
    {
        global $DB;
        if ($questions = $DB->get_records('local_cria_question', ['intent_id' => $this->intent_id], 'timemodified DESC')) {
            foreach ($questions as $question) {
                // Get unindexed (unpublished) examples
                $question_examples = $DB->get_records(
                    'local_cria_question_example',
                    [
                        'questionid' => $question->id,
                        'indexed' => 0
                    ],
                    'id');
                if ($question_examples) {
                    $question->published = false;
                }
            }
            return array_values($questions);
        }
        return false;
    }

    /**
     * Delete all questions
     * @return int
     * @throws \dml_exception
     */
    public function delete_all(): int
    {
        global $DB;
        $INTENT = new intent($this->intent_id);
        // Get all questions
        $questions = $DB->get_records('local_cria_question', ['intent_id' => $this->intent_id], null, 'id');
        // Loop through all questions and delete examples
        foreach ($questions as $question) {
           if (!$delete = $DB->delete_records('local_cria_question_example', ['questionid' => $question->id]))
           {
               return 404;
           }
        }
        // Get all questions stored on creabot server
        $criabot_questions = criabot::question_list($INTENT->get_bot_name());
        // Loop through all questions and delete them from creabot server
        foreach ($criabot_questions->document_names as $question) {
            criabot::question_delete($INTENT->get_bot_name(), $question);
        }
        if (!$delete_questions = $DB->delete_records('local_cria_question', ['intent_id' => $this->intent_id])) {
            return 404;
        }

        return 200;
    }
}