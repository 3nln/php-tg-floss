<?php

namespace App\Conversations;

use App\Buttons\InlineButtons\UsefulCommandConversationButtons;
use App\Utils\Traits\GeneralTrait;
use App\Utils\Traits\UsefulCommandTrait;
use Psr\SimpleCache\InvalidArgumentException;
use SergiX44\Nutgram\Conversations\Conversation;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Telegram\Properties\ParseMode;

class UsefulCommandConversation extends Conversation
{
    use UsefulCommandTrait, UsefulCommandConversationButtons, GeneralTrait;
    protected string $message = <<<HTML
        <b>PHPga oid foydali materiallar</b>
        
        Agar o'ingizdan material qo'shmoqchi bo'lsangiz <a href="https://github.com/phpuzb/telegram/blob/main/src/app/Utils/assets/useful.json">useful.json</a> ni yangilang!
        HTML;

    /**
     * @throws InvalidArgumentException
     */
    public function start(Nutgram $bot): void
    {
        $bot->sendMessage(
            text: $this->message,
            parse_mode: ParseMode::HTML,
            reply_markup: $this->titlesKeyboard($this->parseTitles())
        );

        $this->next("moreAboutUseful");
    }

    /**
     * @throws InvalidArgumentException
     */
    protected function moreAboutUseful(Nutgram $bot): void
    {
        if ($this->checkIfGoingBack($bot)) {
            $bot->editMessageText(
                text: $this->message,
                parse_mode: ParseMode::HTML,
                reply_markup: $this->titlesKeyboard($this->parseTitles())
            );
            $this->next("moreAboutUseful");
            return;
        }

        $title = $bot->callbackQuery()?->data;
        if (is_null($title)) {
            $bot->sendMessage(
                text: "Ko'rish uchun yuqoridagi tugmalardan birini tanlang",
            );
            return;
        }
        $parsed_useful_links = $this->parseUsefulByTitle($title);
        $bot->editMessageText(
            text: "Siz hozir <b>{$title}</b> bo'limidasiz. Ushbu bo'limdan kerakli materiallarni tanlang",
            parse_mode: ParseMode::HTML,
            reply_markup: $this->usefulPageKeyboard($parsed_useful_links)
        );
        $this->next("moreAboutUseful");
    }


}