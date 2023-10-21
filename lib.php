<?php

use theme_fakesmarts\navdrawer;
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

    if (has_capability('local/cria:view_bots', $context)) {
        $items[] = navdrawer::add(
            get_string('bots', 'local_cria'),
            null,
            new moodle_url('/local/cria/bot_config.php'),
            'bi-robot',
        );
    }

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