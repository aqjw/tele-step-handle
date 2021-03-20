<?php

namespace Aqjw\TeleStepHandler\Steps;

class TeleStepButton extends TeleStepItem
{
    public function __construct(string $button, callable $callback)
    {
        parent::__construct(function ($args) use ($button) {
            return $button == $args['button'];
        }, $callback);
    }
}
