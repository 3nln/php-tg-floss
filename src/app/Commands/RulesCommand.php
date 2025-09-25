<?php

namespace App\Commands;

use App\Buttons\InlineButtons\CommandInlineButtons;
use SergiX44\Nutgram\Handlers\Type\Command;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Telegram\Properties\ParseMode;

class RulesCommand extends Command
{
    use CommandInlineButtons;
    protected string $command = 'rules';

    protected string $message = <<<HTML
        <b>Hurmatli guruh a'zosi...</b>
        
        Iltimos qoidalarga oz bo'lsada vaqt ajratishni unutmang, bu muhim! Ushbu guruhda quyidagi harakatlar taqiqlanadi:
        
        <code>* Besabab bir-birini kamsitish yoki so'kinish</code>
        <code>* Sababsiz guruhga spam yozaverish yoki tashash</code>
        <code>* So'ralgan narsani yana qayta ezmalash</code>
        <code>* Administratorlarga nisbatan juddayam qattiq kritika</code>
        <code>* Faoliyat ustidan shikoyat qilaverish yoki nolish</code>
        
        <i>Hamda, bizning hamjamiyat Floss O'zbekiston jamiyati a'zosi ekan, uning qoida va standardlariga bo'ysunamiz, rad etilgan qoida va standardlar ro'yxatiga pastdagi tugmalar orqali o'tishingiz mumkin.</i>
        
        <b>Ushbu qoidalarni doimiy tarzda buzish, bir necha ogohlantirishlirga olib keladi yoki butunlay ban!</b>
        HTML;

    public function handle(Nutgram $bot): void
    {
        $bot->sendMessage(
            text: $this->message,
            parse_mode: ParseMode::HTML,
            reply_markup: $this->rulesKeyboard()
        );
    }

}