<?php

namespace TeleStepHandler\Steps;

class TeleStepBotState extends TeleStepItem
{
    public function __construct(string $bot_state, callable $callback)
    {
        parent::__construct(function ($args) use ($bot_state) {
            $user_bot_state = explode(':', $args['user']->bot_state ?? '');
            return $bot_state == $user_bot_state[0] ?? null;
        }, $callback);
    }

    public function get_params($update, $user)
    {
        $data = explode(':', $user->bot_state ?? '');
        return $this->prepare_params($data[1] ?? '');
    }
}
