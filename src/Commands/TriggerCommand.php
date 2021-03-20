<?php

namespace Aqjw\TeleStepHandler\Commands;

use Aqjw\TeleStepHandler\TeleStepHandler;
use WeStacks\TeleBot\Handlers\CommandHandler;
use WeStacks\TeleBot\Objects\Update;
use WeStacks\TeleBot\TeleBot;

class TriggerCommand extends CommandHandler
{
    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        TeleStepHandler::apply($this->update, $this);
    }

    public static function trigger($update, $bot)
    {
        return true;
    }
}
