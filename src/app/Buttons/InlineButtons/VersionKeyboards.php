<?php

namespace App\Buttons\InlineButtons;

use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardButton;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardMarkup;

trait VersionKeyboards
{
    function versionButtons(array $items, int $page = 1, int $perPage = 3): InlineKeyboardMarkup
    {
        $totalItems = count($items);
        $totalPages = (int)ceil($totalItems / $perPage);

        $page = max(1, min($page, $totalPages));

        $offset = ($page - 1) * $perPage;
        $currentItems = array_slice($items, $offset, $perPage);

        $keyboard = InlineKeyboardMarkup::make();

        foreach ($currentItems as $index => $item) {
            $keyboard->addRow(
                InlineKeyboardButton::make(
                    text: $item,
                    url: "https://github.com/php/php-src/releases/tag/".$item
                )
            );
        }

        $paginationRow = [];

        if ($page > 1) {
            $paginationRow[] = InlineKeyboardButton::make(
                text: "⬅️ Oldingi",
                callback_data: "page " . ($page - 1)
            );
        }

        if ($page < $totalPages) {
            $paginationRow[] = InlineKeyboardButton::make(
                text: "Keyingi ➡️",
                callback_data: "page " . ($page + 1)
            );
        }

        if (!empty($paginationRow)) {
            $keyboard->addRow(...$paginationRow);
        }

        return $keyboard;
    }

}