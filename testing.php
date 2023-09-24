<?php
require_once('../../config.php');

use local_cria\cria;
use local_cria\bot;
use local_cria\gpt;

// CHECK And PREPARE DATA
global $CFG, $OUTPUT, $SESSION, $PAGE, $DB, $COURSE, $USER;

require_login(1, false);
$context = context_system::instance();

\local_cria\base::page(
    new moodle_url('/local/cria/testing.php'),
    get_string('pluginname', 'local_cria'),
    'Testing',
    $context
);



//**************** ******
//*** DISPLAY HEADER ***
//**********************
echo $OUTPUT->header();

$file_name = '8a51e01f-eb69-4f3b-9cc5-a8846a004a50';
$bot_id = 33;
$prompt = "Write a network governance policy for York University. Please include that users must use the York University VPN when working remotely.";;
$content = '';

$BOT = new bot($bot_id);

$json = '[
{
    "criteria_id": "37",
    "description": "Course Concepts & Terminology: \r\nDid the student answer the question make strong and direct connections with course readings and\/or real-life situations? Did they use course language correctly, source corroborating material and make citations properly?\r\n",
    "score": "0.00000",
    "definition": "The student demonstrates an unacceptable skill & ability level (“>F”)"
},
{
    "criteria_id": "37",
    "description": "Course Concepts & Terminology: \r\nDid the student answer the question make strong and direct connections with course readings and\/or real-life situations? Did they use course language correctly, source corroborating material and make citations properly?\r\n",
    "score": "1.00000",
    "definition": "The student demonstrates an inadequate skill & ability level (“>D+”)"
},
{
    "criteria_id": "37",
    "description": "Course Concepts & Terminology: \r\nDid the student answer the question make strong and direct connections with course readings and\/or real-life situations? Did they use course language correctly, source corroborating material and make citations properly?\r\n",
    "score": "2.00000",
    "definition": "The student demonstrates a competent skill & ability level (“C\/C+”)"
},
{
    "criteria_id": "37",
    "description": "Course Concepts & Terminology: \r\nDid the student answer the question make strong and direct connections with course readings and\/or real-life situations? Did they use course language correctly, source corroborating material and make citations properly?\r\n",
    "score": "3.00000",
    "definition": "The student demonstrates a proficient skill & ability level (“B\/B+”)"
},
{
    "criteria_id": "37",
    "description": "Course Concepts & Terminology: \r\nDid the student answer the question make strong and direct connections with course readings and\/or real-life situations? Did they use course language correctly, source corroborating material and make citations properly?\r\n",
    "score": "4.00000",
    "definition": "The student demonstrates an advanced skill & ability level (“A\/A+)"
},
{
    "criteria_id": "38",
    "description": "Information Literacy, Inquiry & Modelling: \r\nDid the student complete their self-evaluation, address where there are gaps in the work, makes reference to course material that supports why this gap exists and make one suggestion from course material to address this gap?\r\n",
    "score": "0.00000",
    "definition": "The student demonstrates an unacceptable skill & ability level (“>F”)"
},
{
    "criteria_id": "38",
    "description": "Information Literacy, Inquiry & Modelling: \r\nDid the student complete their self-evaluation, address where there are gaps in the work, makes reference to course material that supports why this gap exists and make one suggestion from course material to address this gap?\r\n",
    "score": "1.00000",
    "definition": "The student demonstrates an inadequate skill & ability level (“>D+”)"
},
{
    "criteria_id": "38",
    "description": "Information Literacy, Inquiry & Modelling: \r\nDid the student complete their self-evaluation, address where there are gaps in the work, makes reference to course material that supports why this gap exists and make one suggestion from course material to address this gap?\r\n",
    "score": "2.00000",
    "definition": "The student demonstrates a competent skill & ability level (“C\/C+”)"
},
{
    "criteria_id": "38",
    "description": "Information Literacy, Inquiry & Modelling: \r\nDid the student complete their self-evaluation, address where there are gaps in the work, makes reference to course material that supports why this gap exists and make one suggestion from course material to address this gap?\r\n",
    "score": "3.00000",
    "definition": "The student demonstrates a proficient skill & ability level (“B\/B+”)"
},
{
    "criteria_id": "38",
    "description": "Information Literacy, Inquiry & Modelling: \r\nDid the student complete their self-evaluation, address where there are gaps in the work, makes reference to course material that supports why this gap exists and make one suggestion from course material to address this gap?\r\n",
    "score": "4.00000",
    "definition": "The student demonstrates an advanced skill & ability level (“A\/A+)"
},
{
    "criteria_id": "39",
    "description": "Self Determination: \r\nDid the student meet the submission timelines and guidelines?\r\n",
    "score": "0.00000",
    "definition": "The student demonstrates an unacceptable skill & ability level (“>F”)"
},
{
    "criteria_id": "39",
    "description": "Self Determination: \r\nDid the student meet the submission timelines and guidelines?\r\n",
    "score": "1.00000",
    "definition": "The student demonstrates an inadequate skill & ability level (“>D+”)"
},
{
    "criteria_id": "39",
    "description": "Self Determination: \r\nDid the student meet the submission timelines and guidelines?\r\n",
    "score": "2.00000",
    "definition": "The student demonstrates a competent skill & ability level (“C\/C+”)"
},
{
    "criteria_id": "39",
    "description": "Self Determination: \r\nDid the student meet the submission timelines and guidelines?\r\n",
    "score": "3.00000",
    "definition": "The student demonstrates a proficient skill & ability level (“B\/B+”)"
},
{
    "criteria_id": "39",
    "description": "Self Determination: \r\nDid the student meet the submission timelines and guidelines?\r\n",
    "score": "4.00000",
    "definition": "The student demonstrates an advanced skill & ability level (“A\/A+)"
},
{
    "criteria_id": "40",
    "description": "Communication Critical Thinking Problem Solving: \r\nHow well did the student create their own question to answer, articulate why this question was of value to answer, state the steps needed to answer the question, and finally answer the question correctly?\r\n",
    "score": "0.00000",
    "definition": "The student demonstrates an unacceptable skill & ability level (“>F”)"
},
{
    "criteria_id": "40",
    "description": "Communication Critical Thinking Problem Solving: \r\nHow well did the student create their own question to answer, articulate why this question was of value to answer, state the steps needed to answer the question, and finally answer the question correctly?\r\n",
    "score": "1.00000",
    "definition": "The student demonstrates an inadequate skill & ability level (“>D+”)"
},
{
    "criteria_id": "40",
    "description": "Communication Critical Thinking Problem Solving: \r\nHow well did the student create their own question to answer, articulate why this question was of value to answer, state the steps needed to answer the question, and finally answer the question correctly?\r\n",
    "score": "2.00000",
    "definition": "The student demonstrates a competent skill & ability level (“C\/C+”)"
},
{
    "criteria_id": "40",
    "description": "Communication Critical Thinking Problem Solving: \r\nHow well did the student create their own question to answer, articulate why this question was of value to answer, state the steps needed to answer the question, and finally answer the question correctly?\r\n",
    "score": "3.00000",
    "definition": "The student demonstrates a proficient skill & ability level (“B\/B+”)"
},
{
    "criteria_id": "40",
    "description": "Communication Critical Thinking Problem Solving: \r\nHow well did the student create their own question to answer, articulate why this question was of value to answer, state the steps needed to answer the question, and finally answer the question correctly?\r\n",
    "score": "4.00000",
    "definition": "The student demonstrates an advanced skill & ability level (“A\/A+)"
}
]
';


$rubrics = categorize_criteria($json);
$rubric_formatted = '';
foreach ($rubrics as $rubric) {

    for ($i = 0; $i < count($rubric); $i++) {
//        // Separate skill from description
        $skill = explode("\n", $rubric[$i]->description);
//        print_object($skill);
        if ($i == 0) {
            $rubric_formatted .= str_replace("\n", '', $skill[0]) . "(" . $rubric[count($rubric) - 1]->score . " Points)" . "\n";
            unset($skill[0]);
            $rubric_formatted .= implode("\n", $skill);
        } else {
           if ($i == count($rubric) - 1) {
               $rubric_formatted .="\n";
           }
        }
    }
}
print_object($rubric_formatted);
function categorize_criteria($json) {
    $criteria_array = json_decode($json, true);
    $categorized_array = array();
    foreach ($criteria_array as $criteria) {
        $criteria_id = $criteria['criteria_id'];
        if (!isset($categorized_array[$criteria_id])) {
            $categorized_array[$criteria_id] = array();
        }
        array_push($categorized_array[$criteria_id], (object)$criteria);
    }

    return array_values($categorized_array);
}
//$cache = \cache::make('local_cria', 'cria_system_messages');
//$system_message = $cache->set($BOT->get_bot_type() . '_' . sesskey(), $BOT->get_bot_type_system_message() . ' ' . $BOT->get_bot_system_message());
//


//print_object(cria::start_chat($bot_id));
//print_object(cria::send_chat_request($bot_id, $chat_id, $prompt));

//print_object(cria::delete_file($bot_id, $file_name, true));
//print_object(cria::get_files($bot_id));
//print_object(cria::get_chat_summary($bot_id));


//print_object('Starting chat');
//$message = gpt::get_response($bot_id, $prompt, $content);
//print_object($message);
//print_object(cria::get_files(11));
//**********************
//*** DISPLAY FOOTER ***
//**********************
echo $OUTPUT->footer();