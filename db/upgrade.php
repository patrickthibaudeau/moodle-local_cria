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

    return true;
}