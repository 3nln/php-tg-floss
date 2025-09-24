<?php

namespace App\Buttons\InlineButtons;

use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardButton;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardMarkup;

trait CommandInlineButtons
{
    public function startKeyboard() : InlineKeyboardMarkup
    {
        return InlineKeyboardMarkup::make()
            ->addRow(
                InlineKeyboardButton::make(text: 'Jamiyat', url: 'https://t.me/yiiframework_uz'),
                InlineKeyboardButton::make(text: 'Web Sahifa', url: 'https://php.org.uz/'),
            );
    }
}