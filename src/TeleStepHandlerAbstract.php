<?php

namespace Aqjw\TeleStepHandler;

abstract class TeleStepHandlerAbstract
{
    public function set_parameters($bot, $args)
    {
        $this->bot = $bot;
        foreach ($args as $key => $value) {
            $this->$key = $value;
        }
    }
}
