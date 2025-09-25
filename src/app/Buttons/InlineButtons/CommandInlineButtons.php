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
    public function rulesKeyboard() : InlineKeyboardMarkup
    {
        return InlineKeyboardMarkup::make()
            ->addRow(
                InlineKeyboardButton::make(text: 'Jamiyat', url: 'https://t.me/yiiframework_uz'),
                InlineKeyboardButton::make(text: 'Rad Etilgan Qoidalar', url: 'https://github.com/phpuzb/.github/blob/main/RULES.md'),
            );
    }

    public function aboutKeyboard() : InlineKeyboardMarkup
    {
        return InlineKeyboardMarkup::make()
            ->addRow(
                InlineKeyboardButton::make(text: 'Ochiq Havolalar', url: 'https://github.com/phpuzb/telegram'),
            );
    }
    public function roadmapKeyboard() : InlineKeyboardMarkup
    {
        return InlineKeyboardMarkup::make()
            ->addRow(
                InlineKeyboardButton::make(text: 'Rasmiy Dokumentatsiya', url: 'https://php.net'),
            )->addRow(
                InlineKeyboardButton::make(text: 'PHP The Right Way', url: 'https://phptherightway.com/'),
            )->addRow(
                InlineKeyboardButton::make(text: 'W3Schools PHP', url: 'https://www.w3schools.com/php/'),
            )->addRow(
                InlineKeyboardButton::make(text: 'Laracasts', url: 'https://laracasts.com/'),
            );
    }

    public function usefulCommandKeyboard($sources): InlineKeyboardMarkup
    {
        $inline_keyboard = InlineKeyboardMarkup::make();
        foreach ($sources as $community) {
            $inline_keyboard->addRow(
                InlineKeyboardButton::make(text: $community["title"], callback_data: $community["title"])
            );
        }
        return $inline_keyboard;
    }
}