<?php

namespace Aqjw\TeleStepHandler\Steps;

class TeleStepBotState extends TeleStepItem
{
    public function __construct(string $bot_state, callable $callback)
    {
        parent::__construct(function ($args) use ($bot_state) {
            return $bot_state == $args['user']->bot_state ?? null;
        }, $callback);
    }
}
