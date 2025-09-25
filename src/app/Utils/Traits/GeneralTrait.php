<?php

namespace App\Utils\Traits;

use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Telegram\Types\Command\BotCommand;

trait GeneralTrait
{
    public function checkIfGoingBack(Nutgram $bot): bool
    {
        $data = $bot->callbackQuery()?->data ?? $bot->message()?->text ?? "something_else";
        $back_signs = ["back", "go_back"];
        return in_array($data, $back_signs);
    }

    public function botCommands(): array
    {
        return [
            BotCommand::make('start', 'Botni qayta ishga tushirish'),
            BotCommand::make('rules', 'Jamiyatimiz qoidalari'),
            BotCommand::make('about', 'Ushbu botimizni rivojlantirish haqida'),
            BotCommand::make('roadmap', "Boshlang'ich PHP yo'l xaritasi"),
            BotCommand::make('group', 'PHPga oid guruhlar va hamjamiyatlar'),
            BotCommand::make('useful', "Foydali resurslar ro'yxati"),
        ];
    }
}