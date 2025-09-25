<?php

namespace App\Buttons\InlineButtons;

use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardButton;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardMarkup;

trait GroupConversationButtons
{
    public function communitiesKeyboard($communities): InlineKeyboardMarkup
    {
        $inline_keyboard = InlineKeyboardMarkup::make();
        foreach ($communities as $community) {
            $inline_keyboard->addRow(
                InlineKeyboardButton::make(text: $community["name"], callback_data: $community["slug"])
            );
        }
        return $inline_keyboard;
    }

    public function communityLinkKeyboard($community): InlineKeyboardMarkup
    {
        $link_keys = ["telegram", "web"];
        $button = InlineKeyboardMarkup::make();

        $rowButtons = [];
        foreach ($community as $key => $value) {
            if (in_array($key, $link_keys)) {
                $rowButtons[] = InlineKeyboardButton::make(text: ucfirst($key), url: $value);
            }
        }

        foreach (array_chunk($rowButtons, 2) as $row) {
            $button->addRow(...$row);
        }

        $button->addRow(
            InlineKeyboardButton::make(text: "🔙 Orqaga", callback_data: 'back')
        );

        return $button;
    }

}