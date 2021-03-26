<?php

namespace TeleStepHandler;

abstract class TeleStepHandlerAbstract
{
    public function set_parameters($bot, $args)
    {
        $this->bot = $bot;
        foreach ($args as $key => $value) {
            $this->$key = $value;
        }
    }

    public function is_channel_allowed($telegram_id)
    {
        $channel_access_callback = config('tele_step_handler.channel_access_callback');
        if ($telegram_id === 0 || ! is_callable($channel_access_callback)) return false;
        return call_user_func($channel_access_callback, $telegram_id);
    }
}
