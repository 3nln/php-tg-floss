<?php

namespace App\Commands;

use App\Buttons\InlineButtons\CommandInlineButtons;
use SergiX44\Nutgram\Handlers\Type\Command;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Telegram\Properties\ParseMode;

class RoadmapCommand extends Command
{
    use CommandInlineButtons;
    protected string $command = 'roadmap';

    public function handle(Nutgram $bot): void
    {
        $bot->sendMessage(
            text: file_get_contents(__DIR__ . '/../Utils/assets/roadmap.md'),
            parse_mode: ParseMode::HTML,
            reply_markup: $this->roadmapKeyboard()
        );
    }


}