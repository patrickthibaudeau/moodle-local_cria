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

    return true;
}