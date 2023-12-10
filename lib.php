<?php

use theme_fakesmarts\navdrawer;
use local_cria\bot_role;
use local_cria\bot_roles;
use local_cria\bot_capability;
use local_cria\bot_capabilities;
use local_cria\capability_assign;
use local_cria\capabilities_assign;

/**
 * Build navdrawer menu items
 * @return array
 * @throws coding_exception
 * @throws dml_exception
 */
function local_cria_navdrawer_items()
{
    global $CFG;

    $context = context_system::instance();
    $items = [];
//
//    // Create submenu for import
//    $import_menu = [
//        navdrawer::add(
//            get_string('inventory', 'local_order'),
//            null,
//            '/local/order/import/index.php?import=inventory',
//            'fas fa-boxes'),
//        navdrawer::add(
//            get_string('organizations', 'local_order'),
//            null,
//            '/local/order/import/index.php?import=organization',
//            'fas fa-layer-group',
//            true),
//        navdrawer::add(
//            get_string('campuses', 'local_order'),
//            null,
//            $CFG->wwwroot . '/local/order/import/index.php?import=campus',
//            'fas fa-university'),
//        navdrawer::add(
//            get_string('buildings', 'local_order'),
//            null,
//            $CFG->wwwroot . '/local/order/import/index.php?import=building',
//            'fas fa-building'),
//        navdrawer::add(
//            get_string('floors', 'local_order'),
//            null,
//            $CFG->wwwroot . '/local/order/import/index.php?import=floor',
//            'fas fa-grip-lines'),
//        navdrawer::add(
//            get_string('room_types', 'local_order'),
//            null,
//            $CFG->wwwroot . '/local/order/import/index.php?import=room_type',
//            'fas fa-border-style'),
//        navdrawer::add(
//            get_string('rooms', 'local_order'),
//            null,
//            $CFG->wwwroot . '/local/order/import/index.php?import=room',
//            'fas fa-door-open',
//            true),
//        navdrawer::add(
//            get_string('events', 'local_order'),
//            null,
//            $CFG->wwwroot . '/local/order/import/index.php?import=event',
//            'fas fa-calendar-check',
//            true),
//    ];

    // Only add import submenu if user has capability
    if (has_capability('local/cria:view_providers', $context)) {
        $items[] = navdrawer::add(
            get_string('providers', 'local_cria'),
            null,
            new moodle_url('/local/cria/providers.php'),
            'bi-server',
        );
    }

    // Link to conversation styles
    if (has_capability('local/cria:view_conversation_styles', $context)) {
        $items[] = navdrawer::add(
            get_string('conversation_styles', 'local_cria'),
            null,
            new moodle_url('/local/cria/conversation_styles.php'),
            'bi-chat-left',
        );
    }

    if (has_capability('local/cria:view_models', $context)) {
        $items[] = navdrawer::add(
            get_string('bot_models', 'local_cria'),
            null,
            new moodle_url('/local/cria/bot_models.php'),
            'bi-boxes',
        );
    }

    if (has_capability('local/cria:view_bot_types', $context)) {
        $items[] = navdrawer::add(
            get_string('bot_types', 'local_cria'),
            null,
            new moodle_url('/local/cria/bot_types.php'),
            'bi-body-text',
        );
    }


        $items[] = navdrawer::add(
            get_string('bots', 'local_cria'),
            null,
            new moodle_url('/local/cria/bot_config.php'),
            'bi-robot',
        );


    if (has_capability('local/cria:groups', $context)) {
        $items[] = navdrawer::add(
            get_string('groups', 'local_cria'),
            null,
            new moodle_url('/group/index.php?id=1'),
            'bi-people',
        );
    }

    // Only add reports if user has capability
//    if (has_capability('local/order:reports_view', $context)) {
//        $items[] = navdrawer::add(
//            get_string('reports', 'local_order'),
//            null,
//            $CFG->wwwroot . '/local/order/reports/index.php',
//            'fas fa-file-invoice',
//        );
//    }


    return $items;
}

function local_cria_pluginfile($course, $cm, $context, $filearea, $args, $forcedownload, array $options = array())
{
    global $DB;

    if ($context->contextlevel != CONTEXT_SYSTEM) {
        return false;
    }

//    require_login(1, true);

    $fileAreas = array(
        'provider',
        'bot',
        'content'
    );

    if (!in_array($filearea, $fileAreas)) {
        return false;
    }


    $itemid = array_shift($args);
    $filename = array_pop($args);
    $path = !count($args) ? '/' : '/' . implode('/', $args) . '/';

    $fs = get_file_storage();

    $file = $fs->get_file($context->id, 'local_cria', $filearea, $itemid, $path, $filename);

    // If the file does not exist.
    if (!$file) {
        send_file_not_found();
    }

    send_stored_file($file, 86400, 0, $forcedownload); // Options.
}

/**
 * @param $capability
 * @param $bot_id
 * @return true
 */
function has_bot_capability($capability, $bot_id, $user_id = null)
{
    global $USER, $DB;
    // If no user id is provided, use the current user
    if (is_null($user_id)) {
        $user_id = $USER->id;
    }

    // Get user roles
    $CAPABILITIES = new capabilities_assign($bot_id, $user_id);
    $role = $CAPABILITIES->get_record();
    if (is_siteadmin($user_id)) {
        $capabilites = $CAPABILITIES->get_user_capabilities();
    } else {
        $capabilites = $CAPABILITIES->get_user_capabilities($role->bot_role_id);
    }
    // If capability is in array, return true
    if (in_array($capability, $capabilites)) {
        return true;
    }
    return false;

}