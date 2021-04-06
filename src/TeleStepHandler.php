<?php

namespace TeleStepHandler;

use TeleStepHandler\Steps\TeleStepItem;

class TeleStepHandler
{
    private static function get_user($chat)
    {
        $process_user_callback = config('tele_steps.process_user_callback');
        if (empty($chat->id) || ! is_callable($process_user_callback)) return null;
        $name = trim($chat->get('first_name') . ' ' . $chat->get('last_name'));
        if (empty(trim($name))) {
            $name = $chat->title ?? '';
        }

        return call_user_func($process_user_callback, $chat->id, $name);
    }

    private static function prepare_params($update)
    {
        if (empty($update->channel_post)) {
            $message = $update->message ?? $update->callback_query->message ?? null;
            $is_channel = false;
        } else {
            $message = $update->channel_post;
            $is_channel = true;
        }

        $command = null;
        if (! empty($message->entities[0]->type) && $message->entities[0]->type == 'bot_command') {
            $command = $update->message->text ?? $message->text ?? '';
        }

        $button = explode(':', $update->callback_query->data ?? '');

        return [
            'is_channel' => $is_channel,
            'command' => $command,
            'message' => $message,
            'button' => $button[0] ?? null,
            'text' => $message->text ?? null,
            'phone_number' => $message->contact->phone_number ?? null, // Todo: move to another params
            'user' => self::get_user($message->chat ?? null)
        ];
    }

    public static function apply($update, $bot)
    {
        if(config('tele_steps.action_typing')) {
            try {
                $bot->sendChatAction(['action' => 'typing']);
            } catch (\Exception $e) {}
        }

        $args = self::prepare_params($update);
        if (empty($args['user'])) return false;

        if ($args['is_channel']) {
            $channel_steps = config('tele_steps.steps.channel');
            foreach ($channel_steps as $step_class) {
                $break = false;

                if (class_exists($step_class)) {
                    $break = self::process_step($step_class, $args, $bot, $update);
                }

                if ($break) break;
            }
        } else {
            $person_steps = config('tele_steps.steps.personal');
            foreach ($person_steps as $step_class) {
                $break = false;

                if (class_exists($step_class)) {
                    $break = self::process_step($step_class, $args, $bot, $update);
                }

                if ($break) break;
            }
        }
    }

    private static function process_step($step_class, $args, $bot, $update)
    {
        $break = false;

        $step_object = app()->make($step_class);
        if ($step_object->trigger($args)) {
            $step_object->set_parameters($bot, $args);

            $batch = (array) $step_object->handler();
            foreach ($batch as $step) {
                if ($step instanceof TeleStepItem) {
                    if ($step->condition($args)) {
                        $break = $step->callback($update, $args['user']);
                    }
                    if ($break) break;
                }
            }
        }

        return $break;
    }
}
