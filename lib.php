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

    // Only add import submenu if user has capability
    if (has_capability('local/cria:view_providers', $context)) {
        $items[] = navdrawer::add(
            get_string('providers', 'local_cria'),
            null,
            new moodle_url('/local/cria/providers.php'),
            'bi-server',
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
        'content',
        'answer',
        'bot_icon'
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