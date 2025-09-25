<?php
/** @var Nutgram $bot */

use App\Commands\AboutCommand;
use App\Commands\RoadmapCommand;
use App\Commands\RulesCommand;
use App\Commands\StartCommand;
use App\Conversations\GroupConversation;
use App\Conversations\UsefulCommandConversation;
use SergiX44\Nutgram\Nutgram;

// commands
$bot->onCommand('start', StartCommand::class);
$bot->onCommand('rules', RulesCommand::class);
$bot->onCommand('about', AboutCommand::class);
$bot->onCommand('roadmap', RoadmapCommand::class);
// conversations
$bot->onCommand('group', GroupConversation::class);
$bot->onCommand('useful', UsefulCommandConversation::class);
