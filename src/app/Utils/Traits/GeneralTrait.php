<?php

namespace App\Utils\Traits;

use SergiX44\Nutgram\Nutgram;

trait GeneralTrait
{
    public function checkIfGoingBack(Nutgram $bot): bool
    {
        $data = $bot->callbackQuery()?->data ?? $bot->message()?->text ?? "something_else";
        $back_signs = ["back", "go_back"];
        return in_array($data, $back_signs);
    }
}