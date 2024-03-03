<?php
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

    // Max chunks
    $settings->add( new admin_setting_configtext(
        'local_cria/max_chunks',
        get_string('chunk_limit', 'local_cria'),
        get_string('chunk_limit_help', 'local_cria'),
        65000, PARAM_INT, 6
    ));

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

    $settings->add( new admin_setting_configpasswordunmask(
        'local_cria/criadex_api_key',
        get_string('criadex_api_key', 'local_cria'),
        get_string('criadex_api_key_help', 'local_cria'),
        '', PARAM_TEXT, 255
    ));

    $settings->add( new admin_setting_configtext(
        'local_cria/criaembed_url',
        get_string('criaembed_url', 'local_cria'),
        get_string('criaembed_url_help', 'local_cria'),
        '', PARAM_TEXT, 255
    ));


    //Embedding Server
    $settings->add( new admin_setting_configtext(
        'local_cria/embedding_server_url',
        get_string('embedding_server_url', 'local_cria'),
        get_string('embedding_server_url_help', 'local_cria'),
        '', PARAM_TEXT, 255
    ));

    $settings->add( new admin_setting_configtext(
        'local_cria/compare_text',
        get_string('compare_text_bot_id', 'local_cria'),
        get_string('compare_text_bot_id_help', 'local_cria'),
        0, PARAM_INT, 10
    ));

    // Cohere api key
    $settings->add( new admin_setting_configpasswordunmask(
        'local_cria/cohere_api_key',
        get_string('cohere_api_key', 'local_cria'),
        get_string('cohere_api_key_help', 'local_cria'),
        '', PARAM_TEXT, 255
    ));

    // MinutesMaster id
    $settings->add( new admin_setting_configtext(
        'local_cria/minutes_master',
        get_string('minutes_master_id', 'local_cria'),
        get_string('minutes_master_id_help', 'local_cria'),
        '', PARAM_INT, 10
    ));

    // Translate id
    $settings->add( new admin_setting_configtext(
        'local_cria/translate',
        get_string('translate_id', 'local_cria'),
        get_string('translate_id_help', 'local_cria'),
        '', PARAM_INT, 10
    ));

    // Second Opinion id
    $settings->add( new admin_setting_configtext(
        'local_cria/secondopinion',
        get_string('secondopinion_id', 'local_cria'),
        get_string('secondopinion_id_help', 'local_cria'),
        '', PARAM_INT, 10
    ));

    // Supported languages
    $settings->add( new admin_setting_configtextarea(
        'local_cria/languages',
        get_string('languages', 'local_cria'),
        get_string('languages_help', 'local_cria'),
        '', PARAM_TEXT, 255
    ));

    // Faculties
    $settings->add( new admin_setting_configtextarea(
        'local_cria/faculties',
        get_string('faculties', 'local_cria'),
        get_string('faculties_help', 'local_cria'),
        '', PARAM_TEXT, 255
    ));

    // Programs
    $settings->add( new admin_setting_configtextarea(
        'local_cria/programs',
        get_string('programs', 'local_cria'),
        get_string('programs_help', 'local_cria'),
        '', PARAM_TEXT, 255
    ));




    $ADMIN->add('localplugins', $settings);

    // phpcs:ignore Generic.CodeAnalysis.EmptyStatement.DetectedIf
    if ($ADMIN->fulltree) {
        // TODO: Define actual plugin settings page and add it to the tree - {@link https://docs.moodle.org/dev/Admin_settings}.
    }
}
