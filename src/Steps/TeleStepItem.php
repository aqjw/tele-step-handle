<?php

namespace Aqjw\TeleStepHandler\Steps;

class TeleStepItem
{
    private $_condition;
    private $_callback;

    public function __construct(callable $condition, callable $callback)
    {
        $this->_condition = $condition;
        $this->_callback = $callback;
    }

    public function condition($args)
    {
        return call_user_func($this->_condition, $args);
    }

    public function callback()
    {
        return call_user_func($this->_callback);
    }
}
