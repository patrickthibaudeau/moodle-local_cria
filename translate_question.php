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



require_once("../../config.php");

use local_cria\base;
use local_cria\intent;


global $CFG, $OUTPUT, $USER, $PAGE, $DB, $SITE;

$context = CONTEXT_SYSTEM::instance();

// Get config for cria
$config = get_config('local_cria');
$available_languages = explode("\n", $config->languages);
$languages = [];
foreach($available_languages as $lang) {
    $lang = explode('|', $lang);
    $languages[$lang[0]] = $lang[1];
}

require_login(1, false);
$question_id = required_param('question_id',  PARAM_INT);
$lang = required_param('lang',  PARAM_TEXT);

// Get question record
$question = $DB->get_record('local_cria_question', array('id' => $question_id));
$question_examples = $DB->get_records('local_cria_question_example', array('questionid' => $question_id));

$INTENT = new intent($question->intent_id);

// Get translation for the question and creat a new record
$question_translation = $INTENT->translate_question($question->value, $languages[$lang]);
$answer_translation = $INTENT->translate_question($question->answer, $languages[$lang]);
$params = [
    'value' => $question_translation,
    'answer' => $answer_translation,
    'lang' => $lang,
    'faculty' => $question->faculty,
    'program' => $question->program,
    'intent_id' => $question->intent_id,
    'parent_id' => $question->id,
    'usermodified' => $USER->id,
    'timemodified' => time(),
    'timecreated' => time(),
];

$new_question = $DB->insert_record('local_cria_question', $params);
// Now do the same with all question examples
foreach($question_examples as $example) {
    $example_translation = $INTENT->translate_question($example->value, $languages[$lang]);
    $params = [
        'value' => $example_translation,
        'questionid' => $new_question,
        'usermodified' => $USER->id,
        'timemodified' => time(),
        'timecreated' => time(),
    ];
    $DB->insert_record('local_cria_question_example', $params);
}

redirect($CFG->wwwroot . '/local/cria/content.php?id=' . $INTENT->get_bot_id());

base::page(
    new moodle_url('/local/cria/translate_question.php', ['question_id' => $question_id, 'lang' => $lang]),
    get_string('Translating', 'local_cria'),
    '',
    $context,
    'standard'
);

echo $OUTPUT->header();
//**********************
//*** DISPLAY HEADER ***
//

echo 'Translating...';
//**********************
//*** DISPLAY FOOTER ***
//**********************
echo $OUTPUT->footer();
?>