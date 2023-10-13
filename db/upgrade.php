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

    return true;
}