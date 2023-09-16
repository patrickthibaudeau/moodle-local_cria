<?php
defined('MOODLE_INTERNAL') || die();

function xmldb_local_cria_upgrade($oldversion) {
    global $DB;

    $dbman = $DB->get_manager();

    return true;
}