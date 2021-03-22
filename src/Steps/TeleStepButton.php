<?php

namespace TeleStepHandler\Steps;

class TeleStepButton extends TeleStepItem
{
    public function __construct(string $button, callable $callback)
    {
        parent::__construct(function ($args) use ($button) {
            return $button == $args['button'];
        }, $callback);
    }

    public function get_params($update, $user)
    {
        $data = explode(':', $update->callback_query->data ?? '');
        return $this->prepare_params($data[1] ?? '');
    }
}
