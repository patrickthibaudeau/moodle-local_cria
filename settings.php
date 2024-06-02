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


// This file is part of Moodle - https://moodle.org/
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
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.

/**
 * Plugin administration pages are defined here.
 *
 * @package     local_aiquestions
 * @category    admin
 * @copyright   2023 Ruthy Salomon <ruthy.salomon@gmail.com> , Yedidia Klein <yedidia@openapp.co.il>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

if ($hassiteconfig) {
    $settings = new admin_settingpage('local_cria_settings', new lang_string('pluginname', 'local_cria'));

    //Bot Server
    $settings->add( new admin_setting_configtext(
        'local_cria/criabot_url',
        get_string('criabot_url', 'local_cria'),
        get_string('criabot_url_help', 'local_cria'),
        '', PARAM_TEXT, 255
    ));

    $settings->add( new admin_setting_configtext(
        'local_cria/criadex_url',
        get_string('criadex_url', 'local_cria'),
        get_string('criadex_url_help', 'local_cria'),
        '', PARAM_TEXT, 255
    ));

    $settings->add( new admin_setting_configtext(
        'local_cria/criaparse_url',
        get_string('criaparse_url', 'local_cria'),
        get_string('criaparse_url_help', 'local_cria'),
        '', PARAM_TEXT, 255
    ));

    $settings->add( new admin_setting_configtext(
        'local_cria/criaembed_url',
        get_string('criaembed_url', 'local_cria'),
        get_string('criaembed_url_help', 'local_cria'),
        '', PARAM_TEXT, 255
    ));

    $settings->add( new admin_setting_configpasswordunmask(
        'local_cria/criadex_api_key',
        get_string('criadex_api_key', 'local_cria'),
        get_string('criadex_api_key_help', 'local_cria'),
        '', PARAM_TEXT, 255
    ));

    // Cohere api key
    $settings->add( new admin_setting_configpasswordunmask(
        'local_cria/cohere_api_key',
        get_string('cohere_api_key', 'local_cria'),
        get_string('cohere_api_key_help', 'local_cria'),
        '', PARAM_TEXT, 255
    ));

    // ConvertApi api key
    // https://www.convertapi.com
    $settings->add( new admin_setting_configpasswordunmask(
        'local_cria/convertapi_api_key',
        get_string('convertapi_api_key', 'local_cria'),
        get_string('convertapi_api_key_help', 'local_cria'),
        '', PARAM_TEXT, 255
    ));

    $ADMIN->add('localplugins', $settings);

    // phpcs:ignore Generic.CodeAnalysis.EmptyStatement.DetectedIf
    if ($ADMIN->fulltree) {
        // TODO: Define actual plugin settings page and add it to the tree - {@link https://docs.moodle.org/dev/Admin_settings}.
    }
}
