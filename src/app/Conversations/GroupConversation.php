<?php

namespace App\Conversations;

use App\Buttons\InlineButtons\GroupConversationButtons;
use App\Utils\Traits\CommunitiesTrait;
use App\Utils\Traits\GeneralTrait;
use Psr\SimpleCache\InvalidArgumentException;
use SergiX44\Nutgram\Conversations\Conversation;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Telegram\Properties\ParseMode;

class GroupConversation extends Conversation
{
    use GroupConversationButtons, CommunitiesTrait, GeneralTrait;

    protected string $message = <<<HTML
        <b> Telegramdagi PHP Hamjamiyatlari yoki Guruhlari: </b>
        Agar o'zingizni guruhingizni qo'shmoqchi bo'lsangiz, bizni  <a href="https://github.com/phpuzb/telegram/blob/main/src/app/Utils/assets/community.json">community.json</a> ni yangilang!
        HTML;
    /**
     * @throws InvalidArgumentException
     */
    public function start(Nutgram $bot): void
    {

        $bot->sendMessage(
            text: $this->message,
            parse_mode: ParseMode::HTML,
            reply_markup: $this->communitiesKeyboard(communities: $this->parseCommunities())
        );

        $this->next("moreAboutCommunity");
    }

    /**
     * @throws InvalidArgumentException
     */
    public function moreAboutCommunity(Nutgram $bot): void
    {
        // handle back button
        if ($this->checkIfGoingBack($bot)){
            $bot->editMessageText(
                text: $this->message,
                parse_mode: ParseMode::HTML,
                reply_markup: $this->communitiesKeyboard(communities: $this->parseCommunities())
            );
            $this->next("moreAboutCommunity");
            return;
        }
        $slug = $bot->callbackQuery()?->data;
        if (is_null($slug)) {
            $bot->sendMessage(
                text: "Ko'rish uchun yuqoridagi tugmalardan birini tanlang",
            );
            return;
        }
        $message = $this->parseCommunityBySlug($slug);
        $bot->editMessageText(
            text: $this->communityPageText($message),
            parse_mode: ParseMode::HTML,
            reply_markup: $this->communityLinkKeyboard(community: $message)
        );

        $this->next("moreAboutCommunity");
    }
}