<?php

namespace App\Utils\Traits;

use JsonException;
use RuntimeException;
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
            BotCommand::make('version', "PHPning so'nggi versiyalari"),
        ];
    }

    /**
     * @throws JsonException
     */
    public function request(string $url, int $timeout = 10): array
    {
        $ch = curl_init($url);

        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_TIMEOUT => $timeout,
            CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.3',
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error    = curl_error($ch);

        curl_close($ch);

        if ($response === false) {
            throw new RuntimeException("cURL error: $error");
        }

        if ($httpCode < 200 || $httpCode >= 300) {
            throw new RuntimeException("Unexpected HTTP status: $httpCode");
        }

        $data = json_decode($response, true, flags: JSON_THROW_ON_ERROR);

        if (!is_array($data)) {
            throw new RuntimeException("Invalid JSON response from $url");
        }
        return $data;
    }
}