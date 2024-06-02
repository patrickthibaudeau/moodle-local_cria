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


// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Install script for mod_bigbluebuttonbn.
 *
 * @package    mod_bigbluebuttonbn
 * @copyright  2022 Mihail Geshoski <mihail@moodle.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Perform the post-install procedures.
 */
function xmldb_local_cria_install()
{
    global $DB, $USER;

    // Create a record for factual bot types
    $record = new stdClass();
    $record->name = 'Factual';
    $record->description = 'Factual bots are designed to provide factual information to the user.';
    $record->use_bot_server = 1;
    $record->system_message = 'You are a Factual Research AI Assistant dedicated to providing accurate information. ';
    $record->system_message .= 'Your primary task is to assist me by providing me reliable and clear responses to my questions, ';
    $record->system_message .= 'based on the information available in the knowledge base as your only source. ';
    $record->system_message .= 'Refrain from mentioning ‘unstructured knowledge base’ or file names during the conversation. ';
    $record->system_message .= 'You are reluctant of making any claims unless they are stated or supported by the knowledge base. ';
    $record->system_message .= 'In instances where a definitive answer is unavailable, always reply "I don\'t have that information." ';
    $record->system_message .= 'and do not provide any other answer. Your response must be in the same language as my message.';
    $record->timecreated = time();
    $record->timemodified = time();
    $record->usermodified = $USER->id;
    // Insert record;
    $DB->insert_record('local_cria_type', $record);

    // Create a record for meeting notes bot types
    $record = new stdClass();
    $record->name = 'Transcript to Meeting minutes';
    $record->description = 'Meeting Notes bots are designed to convert transcription of a meeting into a set of meeting notes with action items.';
    $record->use_bot_server = 0;
    $record->system_message = 'You are a factual bot that creates meeting notes from the transcript provided. ';
    $record->system_message .= 'You do not add content that is not available in the transcript. ';
    $record->system_message .= 'Your response must be in the same language as the transcript provided. ';
    $record->timecreated = time();
    $record->timemodified = time();
    $record->usermodified = $USER->id;
    // Insert record;
    $DB->insert_record('local_cria_type', $record);


    // Create a record for meeting notes bot types
    $record = new stdClass();
    $record->name = 'Legal Documents';
    $record->description = 'This bot creates legal documents based on information available in Ontario';
    $record->use_bot_server = 0;
    $record->system_message = 'You are a bot that creates legal documents specifically for universities in Ontario, ';
    $record->system_message .= ' Canada. You only provide factual information and you never provide false or untrue ';
    $record->system_message .= ' information. You can use factual information from renowned legal websites in Ontario, ';
    $record->system_message .= 'Canada or any Canadian-related articles that serve Ontario, Canada. You do not provide ';
    $record->system_message .= 'assistance with anything but legal matters. You do not answer questions about courses, ';
    $record->system_message .= 'events, financials, mental health or any other topic that does not meet the legal ';
    $record->system_message .= 'documentation requirements.  If the question asked is not related to legal matters, ';
    $record->system_message .= 'please reply, "This is not a legal matter. I cannot help you with this request." ';
    $record->timecreated = time();
    $record->timemodified = time();
    $record->usermodified = $USER->id;
    // Insert record;
    $DB->insert_record('local_cria_type', $record);

}