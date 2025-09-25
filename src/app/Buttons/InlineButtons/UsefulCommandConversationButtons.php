<?php

namespace App\Buttons\InlineButtons;

use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardButton;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardMarkup;

trait UsefulCommandConversationButtons
{
    public function titlesKeyboard($titles): InlineKeyboardMarkup
    {
        $buttons = InlineKeyboardMarkup::make();
        foreach ($titles as $title) {
            $buttons->addRow(
                InlineKeyboardButton::make(text: ucfirst($title["title"]), callback_data: $title["title"])
            );
        }
        return $buttons;
    }

    public function usefulPageKeyboard(array $parsed_useful_links): InlineKeyboardMarkup
    {
        $buttons = InlineKeyboardMarkup::make();
        foreach ($parsed_useful_links as $link) {
            $buttons->addRow(
                InlineKeyboardButton::make(text: ucfirst($link["title"]), url: $link["url"])
            );
        }
        $buttons->addRow(
            InlineKeyboardButton::make(text: "🔙 Orqaga", callback_data: 'back')
        );
        return $buttons;
    }
}