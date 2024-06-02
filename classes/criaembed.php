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



namespace local_cria;

use local_cria\gpt;
use local_cria\bot;
use local_cria\intent;

class criaembed
{
    /****** Web Embed  Management ******/
    /**
     * @param $intent_id
     * @param $api_key
     * @return mixed
     * @throws \dml_exception
     */
    public static function manage_insert($intent_id) {
        // Get Config
        $config = get_config('local_cria');
        $INTENT = new intent($intent_id);
        $BOT = new bot($INTENT->get_bot_id());

        $data = [
            'botAuthKey' => $INTENT->get_bot_api_key(),
            'botTitle' => $BOT->get_title(),
            'botSubTitle' => $BOT->get_subtitle(),
            'botGreeting' => $BOT->get_welcome_message(),
            'botIconUrl' => $BOT->get_icon_url(),
            "botEmbedTheme" => $BOT->get_theme_color(),
            "botEmbedDefaultEnabled" => $BOT->get_embed_enabled_bool(),
            "botEmbedPosition" => $BOT->get_embed_position(),
            "botWatermark" => $BOT->get_bot_watermark_string_bool(),
            "botLocale" => $BOT->get_bot_locale()
        ];
        // Create model
        return gpt::_make_call(
            $config->criaembed_url,
            $INTENT->get_bot_api_key(),
            json_encode($data),
            '/manage/'. $INTENT->get_bot_id()  . '/insert',
            'POST'
        );
    }

    /**
     * @param $intent_id
     * @param $api_key
     * @return mixed
     * @throws \dml_exception
     */
    public static function manage_delete($intent_id) {
        // Get Config
        $config = get_config('local_cria');
        $INTENT = new intent($intent_id);
        // Create model
        return gpt::_make_call(
            $config->criaembed_url,
            $INTENT->get_bot_api_key(),
            '',
            '/manage/'. $INTENT->get_bot_id()  . '/delete',
            'DELETE'
        );
    }

    /**
     * @param $intent_id
     * @param $api_key
     * @return mixed
     * @throws \dml_exception
     */
    public static function manage_update($intent_id) {
        // Get Config
        $config = get_config('local_cria');
        $INTENT = new intent($intent_id);
        $BOT = new bot($INTENT->get_bot_id());

        $data = [
            'botAuthKey' => $INTENT->get_bot_api_key(),
            'botTitle' => $BOT->get_title(),
            'botSubTitle' => $BOT->get_subtitle(),
            'botGreeting' => $BOT->get_welcome_message(),
            'botIconUrl' => $BOT->get_icon_url(),
            "botEmbedTheme" => $BOT->get_theme_color(),
            "botEmbedDefaultEnabled" => $BOT->get_embed_enabled_bool(),
            "botEmbedPosition" => $BOT->get_embed_position(),
            "botWatermark" => $BOT->get_bot_watermark_string_bool(),
            "botLocale" => $BOT->get_bot_locale()
        ];
        // Create model
        return gpt::_make_call(
            $config->criaembed_url,
            $INTENT->get_bot_api_key(),
            json_encode($data),
            '/manage/'. $INTENT->get_bot_id() . '/config',
            'PATCH'
        );
    }

    /**
     * @param $intent_id
     * @param $api_key
     * @return mixed
     * @throws \dml_exception
     */
    public static function manage_get_config($intent_id) {
        // Get Config
        $config = get_config('local_cria');
        $INTENT = new intent($intent_id);
        // Create model
        return gpt::_make_call(
            $config->criaembed_url,
            $INTENT->get_bot_api_key(),
            '',
            '/manage/'. $INTENT->get_bot_id() . '/config',
            'GET'
        );
    }
}