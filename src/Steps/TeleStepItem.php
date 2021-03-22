<?php

namespace TeleStepHandler\Steps;

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

    public function callback($update, $user)
    {
        $params = $this->get_params($update, $user);
        return call_user_func($this->_callback, $params);
    }

    protected function get_params($update, $user)
    {
        return [];
    }

    protected function prepare_params($string)
    {
        $params = [];
        foreach (explode(',', $string) as $param) {
            $res = explode('=', $param);
            if (count($res) == 2) {
                $params[$res[0]] = $res[1];
            }
        }

        return $params;
    }
}
