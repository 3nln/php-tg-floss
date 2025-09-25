<?php
/** @var Nutgram $bot */

use App\Commands\AboutCommand;
use App\Commands\RulesCommand;
use App\Commands\StartCommand;
use SergiX44\Nutgram\Nutgram;

$bot->onCommand('start', StartCommand::class);
$bot->onCommand('rules', RulesCommand::class);
$bot->onCommand('about', AboutCommand::class);