<?php
defined('MOODLE_INTERNAL') || die();

function xmldb_local_cria_upgrade($oldversion) {
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
        $table->add_field('intentid', XMLDB_TYPE_INTEGER, '10', null, null, null, '0');
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

        $field = new xmldb_field('top_k', XMLDB_TYPE_INTEGER, '3', null, null, null, '10', 'top_p');

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

    return true;
}