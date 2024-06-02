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


defined('MOODLE_INTERNAL') || die();

function xmldb_local_cria_upgrade($oldversion)
{
    global $DB;

    $dbman = $DB->get_manager();

    if ($oldversion < 2023100800) {

        // Define field plugin_path to be added to local_cria_bot.
        $table = new xmldb_table('local_cria_bot');
        $field = new xmldb_field('plugin_path', XMLDB_TYPE_CHAR, '255', null, null, null, null, 'system_reserved');

        // Conditionally launch add field plugin_path.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Cria savepoint reached.
        upgrade_plugin_savepoint(true, 2023100800, 'local', 'cria');
    }

    if ($oldversion < 2023100900) {

        // Define field requires_content_prompt to be added to local_cria_bot.
        $table = new xmldb_table('local_cria_bot');
        $field = new xmldb_field('requires_content_prompt', XMLDB_TYPE_INTEGER, '1', null, null, null, '1', 'bot_type');

        // Conditionally launch add field requires_content_prompt.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Define field has_user_prompt to be added to local_cria_bot.
        $table = new xmldb_table('local_cria_bot');
        $field = new xmldb_field('has_user_prompt', XMLDB_TYPE_INTEGER, '1', null, null, null, '0', 'requires_user_prompt');

        // Conditionally launch add field has_user_prompt.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Cria savepoint reached.
        upgrade_plugin_savepoint(true, 2023100900, 'local', 'cria');
    }

    if ($oldversion < 2023101200) {

        // Define field theme_color to be added to local_cria_bot.
        $table = new xmldb_table('local_cria_bot');
        $field = new xmldb_field('theme_color', XMLDB_TYPE_CHAR, '7', null, null, null, '#e31837', 'welcome_message');

        // Conditionally launch add field theme_color.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Cria savepoint reached.
        upgrade_plugin_savepoint(true, 2023101200, 'local', 'cria');
    }

    if ($oldversion < 2023101602) {

        // Define table local_cria_permission to be dropped.
        $table = new xmldb_table('local_cria_permission');

        // Conditionally launch drop table for local_cria_permission.
        if ($dbman->table_exists($table)) {
            $dbman->drop_table($table);
        }

        // Define table local_cria_permission to be created.
        $table = new xmldb_table('local_cria_permission');

        // Adding fields to table local_cria_permission.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('bot_id', XMLDB_TYPE_INTEGER, '10', null, null, null, '0');
        $table->add_field('user_id', XMLDB_TYPE_INTEGER, '10', null, null, null, '0');
        $table->add_field('groupid', XMLDB_TYPE_INTEGER, '10', null, null, null, '0');
        $table->add_field('roleid', XMLDB_TYPE_INTEGER, '10', null, null, null, '0');
        $table->add_field('usermodified', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('timecreated', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('timemodified', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');

        // Adding keys to table local_cria_permission.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);
        $table->add_key('usermodified', XMLDB_KEY_FOREIGN, ['usermodified'], 'user', ['id']);

        // Conditionally launch create table for local_cria_permission.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        // Cria savepoint reached.
        upgrade_plugin_savepoint(true, 2023101602, 'local', 'cria');
    }

    if ($oldversion < 2023102000) {

        // Define table local_cria_bot_capabilities to be created.
        $table = new xmldb_table('local_cria_bot_capabilities');

        // Adding fields to table local_cria_bot_capabilities.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('bot_id', XMLDB_TYPE_INTEGER, '10', null, null, null, '0');
        $table->add_field('capability', XMLDB_TYPE_CHAR, '255', null, null, null, null);
        $table->add_field('permission', XMLDB_TYPE_INTEGER, '10', null, null, null, '-1');
        $table->add_field('usermodified', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('timecreated', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('timemodified', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');

        // Adding keys to table local_cria_bot_capabilities.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);
        $table->add_key('usermodified', XMLDB_KEY_FOREIGN, ['usermodified'], 'user', ['id']);

        // Adding indexes to table local_cria_bot_capabilities.
        $table->add_index('bot_id_capability_idx', XMLDB_INDEX_NOTUNIQUE, ['bot_id', 'capability']);

        // Conditionally launch create table for local_cria_bot_capabilities.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        // Cria savepoint reached.
        upgrade_plugin_savepoint(true, 2023102000, 'local', 'cria');
    }

    if ($oldversion < 2023102100) {

        // Define table local_cria_bot_capabilities to be dropped.
        $table = new xmldb_table('local_cria_bot_capabilities');

        // Conditionally launch drop table for local_cria_bot_capabilities.
        if ($dbman->table_exists($table)) {
            $dbman->drop_table($table);
        }

        // Define table local_cria_bot_capabilities to be dropped.
        $table = new xmldb_table('local_cria_bot_permission');

        // Conditionally launch drop table for local_cria_bot_capabilities.
        if ($dbman->table_exists($table)) {
            $dbman->drop_table($table);
        }

        // Define table local_cria_bot_role to be created.
        $table = new xmldb_table('local_cria_bot_role');

        // Adding fields to table local_cria_bot_role.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('name', XMLDB_TYPE_CHAR, '255', null, null, null, null);
        $table->add_field('shortname', XMLDB_TYPE_CHAR, '255', null, null, null, null);
        $table->add_field('description', XMLDB_TYPE_TEXT, null, null, null, null, null);
        $table->add_field('usermodified', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('timecreated', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('timemodified', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');

        // Adding keys to table local_cria_bot_role.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);
        $table->add_key('usermodified', XMLDB_KEY_FOREIGN, ['usermodified'], 'user', ['id']);

        // Conditionally launch create table for local_cria_bot_role.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        // Define table local_cria_bot_capabilities to be created.
        $table = new xmldb_table('local_cria_bot_capabilities');

        // Adding fields to table local_cria_bot_capabilities.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('bot_role_id', XMLDB_TYPE_INTEGER, '10', null, null, null, '0');
        $table->add_field('capability', XMLDB_TYPE_CHAR, '255', null, null, null, null);
        $table->add_field('permission', XMLDB_TYPE_INTEGER, '10', null, null, null, '-1');
        $table->add_field('usermodified', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('timecreated', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('timemodified', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');

        // Adding keys to table local_cria_bot_capabilities.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);
        $table->add_key('usermodified', XMLDB_KEY_FOREIGN, ['usermodified'], 'user', ['id']);

        // Adding indexes to table local_cria_bot_capabilities.
        $table->add_index('role_capability_idx', XMLDB_INDEX_NOTUNIQUE, ['bot_role_id', 'capability']);

        // Conditionally launch create table for local_cria_bot_capabilities.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        // Define table local_cria_capability_assign to be created.
        $table = new xmldb_table('local_cria_capability_assign');

        // Adding fields to table local_cria_capability_assign.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('bot_role_id', XMLDB_TYPE_INTEGER, '10', null, null, null, '0');
        $table->add_field('user_id', XMLDB_TYPE_INTEGER, '10', null, null, null, '0');
        $table->add_field('groupid', XMLDB_TYPE_INTEGER, '10', null, null, null, '0');
        $table->add_field('usermodified', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('timecreated', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('timemodified', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');

        // Adding keys to table local_cria_capability_assign.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);
        $table->add_key('usermodified', XMLDB_KEY_FOREIGN, ['usermodified'], 'user', ['id']);

        // Conditionally launch create table for local_cria_capability_assign.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }


        // Cria savepoint reached.
        upgrade_plugin_savepoint(true, 2023102100, 'local', 'cria');
    }

    if ($oldversion < 2023110800) {

        // Define table local_cria_intents to be created.
        $table = new xmldb_table('local_cria_intents');

        // Adding fields to table local_cria_intents.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('name', XMLDB_TYPE_CHAR, '50', null, null, null, null);
        $table->add_field('usermodified', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('timecreated', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('timemodified', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');

        // Adding keys to table local_cria_intents.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);
        $table->add_key('usermodified', XMLDB_KEY_FOREIGN, ['usermodified'], 'user', ['id']);

        // Conditionally launch create table for local_cria_intents.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        // Define table local_cria_question to be created.
        $table = new xmldb_table('local_cria_question');

        // Adding fields to table local_cria_question.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('intent_id', XMLDB_TYPE_INTEGER, '10', null, null, null, '0');
        $table->add_field('value', XMLDB_TYPE_TEXT, null, null, null, null, null);
        $table->add_field('lang', XMLDB_TYPE_CHAR, '10', null, null, null, 'en');
        $table->add_field('faculty', XMLDB_TYPE_CHAR, '50', null, null, null, null);
        $table->add_field('program', XMLDB_TYPE_CHAR, '255', null, null, null, null);
        $table->add_field('usermodified', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('timecreated', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('timemodified', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');

        // Adding keys to table local_cria_question.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);
        $table->add_key('usermodified', XMLDB_KEY_FOREIGN, ['usermodified'], 'user', ['id']);

        // Conditionally launch create table for local_cria_question.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        // Define table local_cria_question_example to be created.
        $table = new xmldb_table('local_cria_question_example');

        // Adding fields to table local_cria_question_example.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('questionid', XMLDB_TYPE_INTEGER, '10', null, null, null, '0');
        $table->add_field('value', XMLDB_TYPE_TEXT, null, null, null, null, null);
        $table->add_field('usermodified', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('timecreated', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('timemodified', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');

        // Adding keys to table local_cria_question_example.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);
        $table->add_key('usermodified', XMLDB_KEY_FOREIGN, ['usermodified'], 'user', ['id']);

        // Conditionally launch create table for local_cria_question_example.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        // Define table local_cria_answer to be created.
        $table = new xmldb_table('local_cria_answer');

        // Adding fields to table local_cria_answer.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('questionid', XMLDB_TYPE_INTEGER, '10', null, null, null, null);
        $table->add_field('value', XMLDB_TYPE_TEXT, null, null, null, null, null);
        $table->add_field('program', XMLDB_TYPE_CHAR, '255', null, null, null, null);
        $table->add_field('usermodified', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('timecreated', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('timemodified', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');

        // Adding keys to table local_cria_answer.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);
        $table->add_key('usermodified', XMLDB_KEY_FOREIGN, ['usermodified'], 'user', ['id']);

        // Conditionally launch create table for local_cria_answer.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }
        // Cria savepoint reached.
        upgrade_plugin_savepoint(true, 2023110800, 'local', 'cria');
    }

    if ($oldversion < 2023110801) {

        // Define table local_cria_permission to be dropped.
        $table = new xmldb_table('local_cria_permission');

        // Conditionally launch drop table for local_cria_permission.
        if ($dbman->table_exists($table)) {
            $dbman->drop_table($table);
        }

        // Cria savepoint reached.
        upgrade_plugin_savepoint(true, 2023110801, 'local', 'cria');
    }

    if ($oldversion < 2023110802) {

        // Define field bot_id to be added to local_cria_bot_role.
        $table = new xmldb_table('local_cria_bot_role');
        $field = new xmldb_field('bot_id', XMLDB_TYPE_INTEGER, '10', null, null, null, '0', 'id');

        // Conditionally launch add field bot_id.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Cria savepoint reached.
        upgrade_plugin_savepoint(true, 2023110802, 'local', 'cria');
    }

    if ($oldversion < 2023110803) {

        // Define field sortorder to be added to local_cria_bot_role.
        $table = new xmldb_table('local_cria_bot_role');
        $field = new xmldb_field('sortorder', XMLDB_TYPE_INTEGER, '10', null, null, null, '0', 'description');

        // Conditionally launch add field sortorder.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Cria savepoint reached.
        upgrade_plugin_savepoint(true, 2023110803, 'local', 'cria');
    }

    if ($oldversion < 2023110900) {

        // Define table local_cria_bot_capabilities to be dropped.
        $table = new xmldb_table('local_cria_bot_capabilities');

        // Conditionally launch drop table for local_cria_bot_capabilities.
        if ($dbman->table_exists($table)) {
            $dbman->drop_table($table);
        }
        // Define table local_cria_bot_capabilities to be created.
        $table = new xmldb_table('local_cria_bot_capabilities');

        // Adding fields to table local_cria_bot_capabilities.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '19', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('bot_role_id', XMLDB_TYPE_INTEGER, '19', null, null, null, '0');
        $table->add_field('capability', XMLDB_TYPE_CHAR, '255', null, null, null, null);
        $table->add_field('permission', XMLDB_TYPE_INTEGER, '1', null, null, null, '-1');
        $table->add_field('usermodified', XMLDB_TYPE_INTEGER, '19', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('timecreated', XMLDB_TYPE_INTEGER, '19', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('timemodified', XMLDB_TYPE_INTEGER, '19', null, XMLDB_NOTNULL, null, '0');

        // Adding keys to table local_cria_bot_capabilities.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);
        $table->add_key('usermodified', XMLDB_KEY_FOREIGN, ['usermodified'], 'user', ['id']);

        // Conditionally launch create table for local_cria_bot_capabilities.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }
        // Cria savepoint reached.
        upgrade_plugin_savepoint(true, 2023110900, 'local', 'cria');
    }

    if ($oldversion < 2023110901) {

        // Define field system_reserved to be added to local_cria_bot_role.
        $table = new xmldb_table('local_cria_bot_role');
        $field = new xmldb_field('system_reserved', XMLDB_TYPE_INTEGER, '1', null, null, null, '0', 'description');

        // Conditionally launch add field system_reserved.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Cria savepoint reached.
        upgrade_plugin_savepoint(true, 2023110901, 'local', 'cria');
    }

    if ($oldversion < 2023110903) {

        // Rename field capability on table local_cria_bot_capabilities to NEWNAMEGOESHERE.
        $table = new xmldb_table('local_cria_bot_capabilities');
        $field = new xmldb_field('capability', XMLDB_TYPE_CHAR, '255', null, null, null, null, 'bot_role_id');

        // Launch rename field capability.
        $dbman->rename_field($table, $field, 'name');

        // Cria savepoint reached.
        upgrade_plugin_savepoint(true, 2023110903, 'local', 'cria');
    }

    if ($oldversion < 2023112000) {

        // Define table local_cria_models to be dropped.
        $table = new xmldb_table('local_cria_models');

        // Conditionally launch drop table for local_cria_models.
        if ($dbman->table_exists($table)) {
            $dbman->drop_table($table);
        }


        // Define table local_cria_providers to be created.
        $table = new xmldb_table('local_cria_providers');

        // Adding fields to table local_cria_providers.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('name', XMLDB_TYPE_CHAR, '255', null, null, null, null);
        $table->add_field('usermodified', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('timecreated', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('timemodified', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');

        // Adding keys to table local_cria_providers.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);
        $table->add_key('usermodified', XMLDB_KEY_FOREIGN, ['usermodified'], 'user', ['id']);

        // Conditionally launch create table for local_cria_providers.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        // Define table local_cria_models to be created.
        $table = new xmldb_table('local_cria_models');

        // Adding fields to table local_cria_models.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('provider_id', XMLDB_TYPE_INTEGER, '10', null, null, null, '0');
        $table->add_field('name', XMLDB_TYPE_CHAR, '255', null, null, null, null);
        $table->add_field('value', XMLDB_TYPE_TEXT, null, null, null, null, null);
        $table->add_field('prompt_cost', XMLDB_TYPE_NUMBER, '8, 4', null, null, null, '0');
        $table->add_field('completion_cost', XMLDB_TYPE_NUMBER, '8, 4', null, null, null, '0');
        $table->add_field('usermodified', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('timecreated', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('timemodified', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');

        // Adding keys to table local_cria_models.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);
        $table->add_key('usermodified', XMLDB_KEY_FOREIGN, ['usermodified'], 'user', ['id']);

        // Conditionally launch create table for local_cria_models.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }


        // Cria savepoint reached.
        upgrade_plugin_savepoint(true, 2023112000, 'local', 'cria');
    }

    if ($oldversion < 2023112005) {

        // Define field idnumber to be added to local_cria_providers.
        $table = new xmldb_table('local_cria_providers');
        $field = new xmldb_field('idnumber', XMLDB_TYPE_CHAR, '255', null, null, null, null, 'name');

        // Conditionally launch add field idnumber.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Cria savepoint reached.
        upgrade_plugin_savepoint(true, 2023112005, 'local', 'cria');
    }

    if ($oldversion < 2023112006) {

        // Define field criadex_model_id to be added to local_cria_models.
        $table = new xmldb_table('local_cria_models');
        $field = new xmldb_field('criadex_model_id', XMLDB_TYPE_INTEGER, '10', null, null, null, '0', 'value');

        // Conditionally launch add field criadex_model_id.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Cria savepoint reached.
        upgrade_plugin_savepoint(true, 2023112006, 'local', 'cria');
    }

    if ($oldversion < 2023112008) {

        // Define field llm_models to be added to local_cria_providers.
        $table = new xmldb_table('local_cria_providers');
        $field = new xmldb_field('llm_models', XMLDB_TYPE_TEXT, null, null, null, null, null, 'idnumber');

        // Conditionally launch add field llm_models.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Cria savepoint reached.
        upgrade_plugin_savepoint(true, 2023112008, 'local', 'cria');
    }

    if ($oldversion < 2023112009) {

        // Define field max_tokens to be added to local_cria_bot.
        $table = new xmldb_table('local_cria_bot');
        $field = new xmldb_field('max_tokens', XMLDB_TYPE_INTEGER, '10', null, null, null, '500', 'publish');

        // Conditionally launch add field max_tokens.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Define field temperature to be added to local_cria_bot.
        $field = new xmldb_field('temperature', XMLDB_TYPE_NUMBER, '2, 1', null, null, null, '0.3', 'max_tokens');

        // Conditionally launch add field temperature.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        $field = new xmldb_field('top_p', XMLDB_TYPE_NUMBER, '2, 1', null, null, null, '0.2', 'temperature');

        // Conditionally launch add field top_p.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        $field = new xmldb_field('top_k', XMLDB_TYPE_INTEGER, '3', null, null, null, '1', 'top_p');

        // Conditionally launch add field top_k.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        $field = new xmldb_field('min_relevance', XMLDB_TYPE_NUMBER, '2, 1', null, null, null, '0.9', 'top_k');

        // Conditionally launch add field min_relevance.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        $field = new xmldb_field('max_context', XMLDB_TYPE_INTEGER, '10', null, null, null, '2000', 'min_relevance');

        // Conditionally launch add field max_context.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        $field = new xmldb_field('no_context_message', XMLDB_TYPE_TEXT, null, null, null, null, null, 'max_context');

        // Conditionally launch add field no_context_message.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Cria savepoint reached.
        upgrade_plugin_savepoint(true, 2023112009, 'local', 'cria');
    }

    if ($oldversion < 2023112100) {

        // Define field is_embedding to be added to local_cria_models.
        $table = new xmldb_table('local_cria_models');
        $field = new xmldb_field('is_embedding', XMLDB_TYPE_INTEGER, '1', null, null, null, '0', 'value');

        // Conditionally launch add field is_embedding.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Cria savepoint reached.
        upgrade_plugin_savepoint(true, 2023112100, 'local', 'cria');
    }

    if ($oldversion < 2023112104) {

        // Define field max_tokens to be added to local_cria_models.
        $table = new xmldb_table('local_cria_models');
        $field = new xmldb_field('max_tokens', XMLDB_TYPE_INTEGER, '10', null, null, null, '4092', 'value');

        // Conditionally launch add field max_tokens.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Cria savepoint reached.
        upgrade_plugin_savepoint(true, 2023112104, 'local', 'cria');
    }

    if ($oldversion < 2023112200) {

        // Define table local_cria_convo_styles to be created.
        $table = new xmldb_table('local_cria_convo_styles');

        // Adding fields to table local_cria_convo_styles.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('context', XMLDB_TYPE_CHAR, '16', null, null, null, 'TONE');
        $table->add_field('name', XMLDB_TYPE_CHAR, '255', null, null, null, null);
        $table->add_field('description', XMLDB_TYPE_TEXT, null, null, null, null, null);
        $table->add_field('value', XMLDB_TYPE_TEXT, null, null, null, null, null);
        $table->add_field('publish', XMLDB_TYPE_INTEGER, '1', null, null, null, '0');
        $table->add_field('usermodified', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('timecreated', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('timemodified', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');

        // Adding keys to table local_cria_convo_styles.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);
        $table->add_key('usermodified', XMLDB_KEY_FOREIGN, ['usermodified'], 'user', ['id']);

        // Conditionally launch create table for local_cria_convo_styles.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        // Cria savepoint reached.
        upgrade_plugin_savepoint(true, 2023112200, 'local', 'cria');
    }

    if ($oldversion < 2023112300) {

        // Define field class to be added to local_cria_convo_styles.
        $table = new xmldb_table('local_cria_convo_styles');
        $field = new xmldb_field('class', XMLDB_TYPE_CHAR, '50', null, null, null, null, 'value');

        // Conditionally launch add field class.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Cria savepoint reached.
        upgrade_plugin_savepoint(true, 2023112300, 'local', 'cria');
    }

    if ($oldversion < 2023112301) {

        // Define field bot_api_key to be added to local_cria_bot.
        $table = new xmldb_table('local_cria_bot');
        $field = new xmldb_field('bot_api_key', XMLDB_TYPE_CHAR, '100', null, null, null, null, 'embedding_id');

        // Conditionally launch add field bot_api_key.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Cria savepoint reached.
        upgrade_plugin_savepoint(true, 2023112301, 'local', 'cria');
    }
    if ($oldversion < 2023112400) {

        // Define field document_name to be added to local_cria_question.
        $table = new xmldb_table('local_cria_question');
        $field = new xmldb_field('document_name', XMLDB_TYPE_CHAR, '255', null, null, null, null, 'intent_id');

        // Conditionally launch add field document_name.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Cria savepoint reached.
        upgrade_plugin_savepoint(true, 2023112400, 'local', 'cria');
    }

    if ($oldversion < 2023112401) {

        // Changing type of field top_k on table local_cria_bot to number.
        $table = new xmldb_table('local_cria_bot');
        $field = new xmldb_field('top_k', XMLDB_TYPE_NUMBER, '2, 1', null, null, null, '0.9', 'top_p');

        // Launch change of type for field top_k.
        $dbman->change_field_type($table, $field);

        // Cria savepoint reached.
        upgrade_plugin_savepoint(true, 2023112401, 'local', 'cria');
    }

    if ($oldversion < 2023112402) {

        // Changing type of field top_k on table local_cria_bot to int.
        $table = new xmldb_table('local_cria_bot');
        $field = new xmldb_field('top_k', XMLDB_TYPE_INTEGER, '2', null, null, null, '10', 'top_p');

        // Launch change of type for field top_k.
        $dbman->change_field_type($table, $field);

        // Cria savepoint reached.
        upgrade_plugin_savepoint(true, 2023112402, 'local', 'cria');
    }

    if ($oldversion < 2023112403) {

        // Changing type of field top_p on table local_cria_bot to int.
        $table = new xmldb_table('local_cria_bot');
        $field = new xmldb_field('top_p', XMLDB_TYPE_INTEGER, '1', null, null, null, '2', 'temperature');

        // Launch change of type for field top_p.
        $dbman->change_field_type($table, $field);

        // Cria savepoint reached.
        upgrade_plugin_savepoint(true, 2023112403, 'local', 'cria');
    }

    if ($oldversion < 2023112601) {

        // Define table local_cria_intents to be dropped.
        $table = new xmldb_table('local_cria_intents');

        // Conditionally launch drop table for local_cria_intents.
        if ($dbman->table_exists($table)) {
            $dbman->drop_table($table);
        }

        // Cria savepoint reached.
        upgrade_plugin_savepoint(true, 2023112601, 'local', 'cria');
    }
    if ($oldversion < 2023112602) {

        // Define table local_cria_intents to be created.
        $table = new xmldb_table('local_cria_intents');

        // Adding fields to table local_cria_intents.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('bot_id', XMLDB_TYPE_INTEGER, '10', null, null, null, '0');
        $table->add_field('name', XMLDB_TYPE_CHAR, '50', null, null, null, null);
        $table->add_field('description', XMLDB_TYPE_TEXT, null, null, null, null, null);
        $table->add_field('published', XMLDB_TYPE_INTEGER, '1', null, null, null, '0');
        $table->add_field('usermodified', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('timecreated', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('timemodified', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');

        // Adding keys to table local_cria_intents.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);
        $table->add_key('usermodified', XMLDB_KEY_FOREIGN, ['usermodified'], 'user', ['id']);

        // Conditionally launch create table for local_cria_intents.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        // Cria savepoint reached.
        upgrade_plugin_savepoint(true, 2023112602, 'local', 'cria');
    }

    if ($oldversion < 2023112604) {

        // Define field answer to be added to local_cria_question.
        $table = new xmldb_table('local_cria_question');
        $field = new xmldb_field('answer', XMLDB_TYPE_TEXT, null, null, null, null, null, 'value');

        // Conditionally launch add field answer.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Define table local_cria_answer to be dropped.
        $table = new xmldb_table('local_cria_answer');

        // Conditionally launch drop table for local_cria_answer.
        if ($dbman->table_exists($table)) {
            $dbman->drop_table($table);
        }

        // Cria savepoint reached.
        upgrade_plugin_savepoint(true, 2023112604, 'local', 'cria');
    }

    if ($oldversion < 2023112700) {

        // Define field published to be added to local_cria_question.
        $table = new xmldb_table('local_cria_question');
        $field = new xmldb_field('published', XMLDB_TYPE_INTEGER, '1', null, null, null, '0', 'answer');

        // Conditionally launch add field published.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Cria savepoint reached.
        upgrade_plugin_savepoint(true, 2023112700, 'local', 'cria');
    }

    if ($oldversion < 2023112701) {

        // Define field indexed to be added to local_cria_question_example.
        $table = new xmldb_table('local_cria_question_example');
        $field = new xmldb_field('indexed', XMLDB_TYPE_INTEGER, '1', null, null, null, '0', 'value');

        // Conditionally launch add field indexed.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Cria savepoint reached.
        upgrade_plugin_savepoint(true, 2023112701, 'local', 'cria');
    }

    if ($oldversion < 2023120400) {

        // Define field default to be added to local_cria_intents.
        $table = new xmldb_table('local_cria_intents');
        $field = new xmldb_field('is_default', XMLDB_TYPE_INTEGER, '1', null, null, null, '0', 'bot_id');

        // Conditionally launch add field default.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Define field child_bots to be added to local_cria_bot.
        $table = new xmldb_table('local_cria_bot');
        $field = new xmldb_field('child_bots', XMLDB_TYPE_TEXT, null, null, null, null, null, 'theme_color');

        // Conditionally launch add field child_bots.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Cria savepoint reached.
        upgrade_plugin_savepoint(true, 2023120400, 'local', 'cria');
    }

    if ($oldversion < 2023120401) {

        // Define field fine_tunning to be added to local_cria_bot.
        $table = new xmldb_table('local_cria_bot');
        $field = new xmldb_field('fine_tuning', XMLDB_TYPE_INTEGER, '1', null, null, null, '0', 'child_bots');

        // Conditionally launch add field fine_tunning.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Cria savepoint reached.
        upgrade_plugin_savepoint(true, 2023120401, 'local', 'cria');
    }

    if ($oldversion < 2023120403) {

        // Define field bot_api_key to be added to local_cria_intents.
        $table = new xmldb_table('local_cria_intents');
        $field = new xmldb_field('bot_api_key', XMLDB_TYPE_CHAR, '255', null, null, null, null, 'is_default');

        // Conditionally launch add field bot_api_key.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Cria savepoint reached.
        upgrade_plugin_savepoint(true, 2023120403, 'local', 'cria');
    }

    if ($oldversion < 2023120404) {

        // Define field lang to be added to local_cria_intents.
        $table = new xmldb_table('local_cria_intents');
        $field = new xmldb_field('lang', XMLDB_TYPE_CHAR, '5', null, null, null, 'en', 'published');

        // Conditionally launch add field lang.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Define field faculty to be added to local_cria_intents.
        $table = new xmldb_table('local_cria_intents');
        $field = new xmldb_field('faculty', XMLDB_TYPE_CHAR, '10', null, null, null, null, 'lang');

        // Conditionally launch add field faculty.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Define field program to be added to local_cria_intents.
        $table = new xmldb_table('local_cria_intents');
        $field = new xmldb_field('program', XMLDB_TYPE_CHAR, '50', null, null, null, null, 'faculty');

        // Conditionally launch add field program.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Cria savepoint reached.
        upgrade_plugin_savepoint(true, 2023120404, 'local', 'cria');
    }

    if ($oldversion < 2023120405) {

        // Rename field bot_id on table local_cria_files to NEWNAMEGOESHERE.
        $table = new xmldb_table('local_cria_files');
        $field = new xmldb_field('bot_id', XMLDB_TYPE_INTEGER, '10', null, null, null, null, 'id');

        // Launch rename field bot_id.
        $dbman->rename_field($table, $field, 'intent_id');

        // Cria savepoint reached.
        upgrade_plugin_savepoint(true, 2023120405, 'local', 'cria');
    }

    if ($oldversion < 2023120406) {

        // Rename field intent_id on table local_cria_question to NEWNAMEGOESHERE.
        $table = new xmldb_table('local_cria_question');
        $field = new xmldb_field('intentid', XMLDB_TYPE_INTEGER, '10', null, null, null, '0', 'id');

        // Launch rename field intent_id.
        $dbman->rename_field($table, $field, 'intent_id');

        // Cria savepoint reached.
        upgrade_plugin_savepoint(true, 2023120406, 'local', 'cria');
    }

    if ($oldversion < 2023121000) {

        // Define field parent_id to be added to local_cria_question.
        $table = new xmldb_table('local_cria_question');
        $field = new xmldb_field('parent_id', XMLDB_TYPE_INTEGER, '10', null, null, null, '0', 'intent_id');

        // Conditionally launch add field parent_id.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Cria savepoint reached.
        upgrade_plugin_savepoint(true, 2023121000, 'local', 'cria');
    }

    if ($oldversion < 2023121500) {

        // Define field tone to be added to local_cria_bot.
        $table = new xmldb_table('local_cria_bot');
        $field = new xmldb_field('tone', XMLDB_TYPE_CHAR, '20', null, null, null, 'precise', 'no_context_message');

        // Conditionally launch add field tone.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Define field response_length to be added to local_cria_bot.
        $table = new xmldb_table('local_cria_bot');
        $field = new xmldb_field('response_length', XMLDB_TYPE_CHAR, '10', null, null, null, 'short', 'tone');

        // Conditionally launch add field response_length.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Cria savepoint reached.
        upgrade_plugin_savepoint(true, 2023121500, 'local', 'cria');
    }

    if ($oldversion < 2023121800) {

        // Define field generate_answer to be added to local_cria_question.
        $table = new xmldb_table('local_cria_question');
        $field = new xmldb_field('generate_answer', XMLDB_TYPE_INTEGER, '1', null, null, null, '0', 'program');

        // Conditionally launch add field generate_answer.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Define field keywords to be added to local_cria_intents.
        $table = new xmldb_table('local_cria_intents');
        $field = new xmldb_field('keywords', XMLDB_TYPE_TEXT, null, null, null, null, null, 'description');


        // Define field available_child to be added to local_cria_bot.
        $table = new xmldb_table('local_cria_bot');
        $field = new xmldb_field('available_child', XMLDB_TYPE_INTEGER, '1', null, null, null, '0', 'response_length');

        // Conditionally launch add field available_child.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }
        // Conditionally launch add field keywords.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }


        // Cria savepoint reached.
        upgrade_plugin_savepoint(true, 2023121800, 'local', 'cria');
    }

    if ($oldversion < 2023121801) {

        // Define field lang to be added to local_cria_files.
        $table = new xmldb_table('local_cria_files');
        $field = new xmldb_field('lang', XMLDB_TYPE_CHAR, '10', null, null, null, 'en', 'content');

        // Conditionally launch add field lang.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Define field faculty to be added to local_cria_files.
        $table = new xmldb_table('local_cria_files');
        $field = new xmldb_field('faculty', XMLDB_TYPE_CHAR, '50', null, null, null, null, 'lang');

        // Conditionally launch add field faculty.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Define field program to be added to local_cria_files.
        $table = new xmldb_table('local_cria_files');
        $field = new xmldb_field('program', XMLDB_TYPE_CHAR, '255', null, null, null, null, 'faculty');

        // Conditionally launch add field program.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }
        // Cria savepoint reached.
        upgrade_plugin_savepoint(true, 2023121801, 'local', 'cria');
    }

    if ($oldversion < 2023122300) {

        // Define table local_cria_entity to be created.
        $table = new xmldb_table('local_cria_entity');

        // Adding fields to table local_cria_entity.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('bot_id', XMLDB_TYPE_INTEGER, '10', null, null, null, '0');
        $table->add_field('name', XMLDB_TYPE_CHAR, '255', null, null, null, null);
        $table->add_field('usermodified', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('timecreated', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('timemodified', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');

        // Adding keys to table local_cria_entity.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);
        $table->add_key('usermodified', XMLDB_KEY_FOREIGN, ['usermodified'], 'user', ['id']);

        // Conditionally launch create table for local_cria_entity.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        // Define table local_cria_keyword to be created.
        $table = new xmldb_table('local_cria_keyword');

        // Adding fields to table local_cria_keyword.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('entity_id', XMLDB_TYPE_INTEGER, '10', null, null, null, '0');
        $table->add_field('value', XMLDB_TYPE_CHAR, '255', null, null, null, null);
        $table->add_field('usermodified', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('timecreated', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('timemodified', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');

        // Adding keys to table local_cria_keyword.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);
        $table->add_key('usermodified', XMLDB_KEY_FOREIGN, ['usermodified'], 'user', ['id']);

        // Conditionally launch create table for local_cria_keyword.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        // Define table local_cria_synonyms to be created.
        $table = new xmldb_table('local_cria_synonyms');

        // Adding fields to table local_cria_synonyms.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('keyword_id', XMLDB_TYPE_INTEGER, '10', null, null, null, '0');
        $table->add_field('value', XMLDB_TYPE_CHAR, '255', null, null, null, null);
        $table->add_field('usermodified', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('timecreated', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('timemodified', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');

        // Adding keys to table local_cria_synonyms.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);
        $table->add_key('usermodified', XMLDB_KEY_FOREIGN, ['usermodified'], 'user', ['id']);

        // Conditionally launch create table for local_cria_synonyms.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        // Define field keywords to be added to local_cria_files.
        $table = new xmldb_table('local_cria_files');
        $field = new xmldb_field('keywords', XMLDB_TYPE_TEXT, null, null, null, null, null, 'content');

        // Conditionally launch add field keywords.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Define field keywords to be added to local_cria_intents.
        $table = new xmldb_table('local_cria_intents');
        $field = new xmldb_field('keywords', XMLDB_TYPE_TEXT, null, null, null, null, null, 'description');

        // Conditionally launch add field keywords.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Define field keywords to be added to local_cria_question.
        $table = new xmldb_table('local_cria_question');
        $field = new xmldb_field('keywords', XMLDB_TYPE_TEXT, null, null, null, null, null, 'answer');

        // Conditionally launch add field keywords.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Cria savepoint reached.
        upgrade_plugin_savepoint(true, 2023122300, 'local', 'cria');
    }

    if ($oldversion < 2024012600) {

        // Define field no_context_use_message to be added to local_cria_bot.
        $table = new xmldb_table('local_cria_bot');
        $field = new xmldb_field('no_context_use_message', XMLDB_TYPE_INTEGER, '1', null, null, null, '1', 'no_context_message');

        // Conditionally launch add field no_context_use_message.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Define field no_context_llm_guess to be added to local_cria_bot.
        $table = new xmldb_table('local_cria_bot');
        $field = new xmldb_field('no_context_llm_guess', XMLDB_TYPE_INTEGER, '1', null, null, null, '0', 'no_context_use_message');

        // Conditionally launch add field no_context_llm_guess.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Cria savepoint reached.
        upgrade_plugin_savepoint(true, 2024012600, 'local', 'cria');
    }

    if ($oldversion < 2024012601) {

        // Define field title to be added to local_cria_bot.
        $table = new xmldb_table('local_cria_bot');
        $field = new xmldb_field('title', XMLDB_TYPE_CHAR, '255', null, null, null, null, 'fine_tuning');

        // Conditionally launch add field title.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }
        // Define field subtitle to be added to local_cria_bot.
        $table = new xmldb_table('local_cria_bot');
        $field = new xmldb_field('subtitle', XMLDB_TYPE_CHAR, '255', null, null, null, null, 'title');

        // Conditionally launch add field subtitle.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Define field icon_url to be added to local_cria_bot.
        $table = new xmldb_table('local_cria_bot');
        $field = new xmldb_field('icon_url', XMLDB_TYPE_CHAR, '255', null, null, null, null, 'welcome_message');

        // Conditionally launch add field icon_url.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Cria savepoint reached.
        upgrade_plugin_savepoint(true, 2024012601, 'local', 'cria');
    }

    if ($oldversion < 2024012704) {

        // Define field name to be added to local_cria_question.
        $table = new xmldb_table('local_cria_question');
        $field = new xmldb_field('name', XMLDB_TYPE_CHAR, '255', null, null, null, null, 'document_name');

        // Conditionally launch add field name.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Cria savepoint reached.
        upgrade_plugin_savepoint(true, 2024012704, 'local', 'cria');
    }

    if ($oldversion < 2024012801) {

        // Define field embed_enabled to be added to local_cria_bot.
        $table = new xmldb_table('local_cria_bot');
        $field = new xmldb_field('embed_enabled', XMLDB_TYPE_INTEGER, '1', null, null, null, '0', 'icon_url');

        // Conditionally launch add field embed_enabled.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Cria savepoint reached.
        upgrade_plugin_savepoint(true, 2024012801, 'local', 'cria');
    }

    if ($oldversion < 2024012802) {
        // Define field embed_position to be dropped from local_cria_bot.
        $table = new xmldb_table('local_cria_bot');
        $field = new xmldb_field('embed_position');

        // Conditionally launch drop field embed_position.
        if ($dbman->field_exists($table, $field)) {
            $dbman->drop_field($table, $field);
        }

        // Define field embed_position to be added to local_cria_bot.
        $table = new xmldb_table('local_cria_bot');
        $field = new xmldb_field('embed_position', XMLDB_TYPE_INTEGER, '1', null, null, null, '2', 'embed_enabled');

        // Conditionally launch add field embed_position.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Cria savepoint reached.
        upgrade_plugin_savepoint(true, 2024012802, 'local', 'cria');
    }

    if ($oldversion < 2024022801) {

        // Define field rerank_model_id to be added to local_cria_bot.
        $table = new xmldb_table('local_cria_bot');
        $field = new xmldb_field('rerank_model_id', XMLDB_TYPE_INTEGER, '10', null, null, null, null, 'embedding_id');

        // Conditionally launch add field rerank_model_id.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Define field top_n to be added to local_cria_bot.
        $table = new xmldb_table('local_cria_bot');
        $field = new xmldb_field('top_n', XMLDB_TYPE_INTEGER, '2', null, null, null, '3', 'top_k');

        // Conditionally launch add field top_n.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Define field min_k to be added to local_cria_bot.
        $table = new xmldb_table('local_cria_bot');
        $field = new xmldb_field('min_k', XMLDB_TYPE_NUMBER, '2, 1', null, null, null, '0.8', 'top_k');

        // Conditionally launch add field min_k.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Cria savepoint reached.
        upgrade_plugin_savepoint(true, 2024022801, 'local', 'cria');
    }

    if ($oldversion < 2024030300) {

        // Define field file_type to be added to local_cria_files.
        $table = new xmldb_table('local_cria_files');
        $field = new xmldb_field('file_type', XMLDB_TYPE_CHAR, '100', null, null, null, 'docx', 'name');

        // Conditionally launch add field file_type.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Cria savepoint reached.
        upgrade_plugin_savepoint(true, 2024030300, 'local', 'cria');
    }

    if ($oldversion < 2024031400) {

        // Define field email to be added to local_cria_bot.
        $table = new xmldb_table('local_cria_bot');
        $field = new xmldb_field('email', XMLDB_TYPE_CHAR, '255', null, null, null, null, 'requires_user_prompt');

        // Conditionally launch add field email.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Cria savepoint reached.
        upgrade_plugin_savepoint(true, 2024031400, 'local', 'cria');
    }

    if ($oldversion < 2024032300) {

        // Define field botwatermark to be added to local_cria_bot.
        $table = new xmldb_table('local_cria_bot');
        $field = new xmldb_field('botwatermark', XMLDB_TYPE_INTEGER, '1', null, null, null, '0', 'icon_url');

        // Conditionally launch add field botwatermark.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Cria savepoint reached.
        upgrade_plugin_savepoint(true, 2024032300, 'local', 'cria');
    }

    if ($oldversion < 2024040400) {

        // Define field botlocale to be added to local_cria_bot.
        $table = new xmldb_table('local_cria_bot');
        $field = new xmldb_field('bot_locale', XMLDB_TYPE_CHAR, '10', null, null, null, 'en-US', 'theme_color');

        // Conditionally launch add field botlocale.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Cria savepoint reached.
        upgrade_plugin_savepoint(true, 2024040400, 'local', 'cria');
    }

    if ($oldversion < 2024050400) {

        // Define field parse_strategy to be added to local_cria_bot.
        $table = new xmldb_table('local_cria_bot');
        $field = new xmldb_field('parse_strategy', XMLDB_TYPE_CHAR, '50', null, null, null, 'GENERIC', 'email');

        // Conditionally launch add field parse_strategy.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Cria savepoint reached.
        upgrade_plugin_savepoint(true, 2024050400, 'local', 'cria');
    }

    return true;
}