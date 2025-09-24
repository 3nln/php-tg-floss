<?php

namespace App\Commands;

use App\Buttons\InlineButtons\CommandInlineButtons;
use SergiX44\Nutgram\Handlers\Type\Command;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Telegram\Properties\ParseMode;

class StartCommand extends Command
{
    use CommandInlineButtons;
    protected string $command = 'start';

    protected string $message = "Assalomu alaykum! Botga xush kelibsiz. \n\nUshbu bot *PHP Uzbekistan* jamiyati tomonidan yaratilgan bot hisoblanib, O'zbek PHP jamiyati uchun foydali bo'lgan resurslarni yetkazish, saqlash va saralash uchun xizmat qiladi.";
    public function handle(Nutgram $bot): void
    {
        $bot->sendMessage(
            text: $this->message,
            parse_mode: ParseMode::MARKDOWN_LEGACY,
            reply_markup: $this->startKeyboard()
        );
    }
}