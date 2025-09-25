<?php

namespace App\Commands;

use App\Buttons\InlineButtons\CommandInlineButtons;
use SergiX44\Nutgram\Handlers\Type\Command;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Telegram\Properties\ParseMode;

class AboutCommand extends Command
{
    use CommandInlineButtons;
    protected string $command = 'start';

    protected string $message = <<<HTML
        <b>Hurmatli foydalanuvchi!</b>
        
        Bizning botimiz aktiv tarzda shakllantirib boriladi. Buning ustida esa bir necha avtor va dasturchilar turadi, ushbu havolalar orqali bizning sinovchilarimizdan biriga aylaning va biz bilan botimiz, hamda guruhimiz ishlatish qulayligini oshiring.
        HTML;

    public function handle(Nutgram $bot): void
    {
        $bot->sendMessage(
            text: $this->message,
            parse_mode: ParseMode::HTML,
            reply_markup: $this->aboutKeyboard()
        );
    }


}