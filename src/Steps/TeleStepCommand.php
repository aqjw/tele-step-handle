<?php

namespace Aqjw\TeleStepHandler\Steps;

class TeleStepCommand extends TeleStepItem
{
    public function __construct(string $command, callable $callback)
    {
        parent::__construct(function ($args) use ($command) {
            return $command == $args['command'];
        }, $callback);
    }
}
