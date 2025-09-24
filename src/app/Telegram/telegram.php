<?php
/** @var Nutgram $bot */

use App\Commands\StartCommand;
use SergiX44\Nutgram\Nutgram;

$bot->onCommand('start', StartCommand::class);