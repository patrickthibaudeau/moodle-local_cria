<?php

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