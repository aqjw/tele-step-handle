<?php

namespace Aqjw\TeleStepHandler;

use Aqjw\TeleStepHandler\Steps\TeleStepItem;

class TeleStepHandler
{
    private static function get_user($chat)
    {
        $process_user_callback = config('tele_step_handler.process_user_callback');
        if (empty($chat->id) || ! is_callable($process_user_callback)) return null;
        $name = trim($chat->get('first_name') . ' ' . $chat->get('last_name'));

        return call_user_func($process_user_callback, $chat->id, $name);
    }

    private static function prepare_params($update)
    {
        $message = $update->message ?? $update->callback_query->message ?? null;

        $command = null;
        if (! empty($message->entities[0]->type) && $message->entities[0]->type == 'bot_command') {
            $command = $update->message->text;
        }

        return [
            'command' => $command,
            'button' => $update->callback_query->data ?? null,
            'text' => $message->text ?? null,
            'phone_number' => $message->contact->phone_number ?? null,
            'user' => self::get_user($message->chat ?? null)
        ];
    }

    public static function apply($update, $bot)
    {
        if(config('tele_step_handler.action_typing')) {
            $bot->sendChatAction(['action' => 'typing']);
        }

        $args = self::prepare_params($update);
        $steps = config('tele_step_handler.steps');

        foreach ($steps as $step_class) {
            $break = false;

            if (class_exists($step_class)) {
                $step_object = app()->make($step_class);
                if ($step_object->trigger($args)) {
                    $step_object->set_parameters($bot, $args);

                    $batch = (array) $step_object->handler();
                    foreach ($batch as $step) {
                        if ($step instanceof TeleStepItem) {
                            if ($step->condition($args)) {
                                $break = $step->callback();
                            }
                            if ($break) break;
                        }
                    }
                }
            }

            if ($break) break;
        }
    }
}
