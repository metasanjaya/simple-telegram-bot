<?php

include __DIR__ . '/vendor/autoload.php';

use danog\MadelineProto\API;
use Bot\BotEventHandler;
use danog\MadelineProto\Settings;
use danog\MadelineProto\Settings\AppInfo;

$config = json_decode(file_get_contents('config.json'), true);

$bots = [];
$appInfo = new AppInfo();
$appInfo->setApiId($config["app"]["api_id"]);
$appInfo->setApiHash($config["app"]["api_hash"]);
$settings = new Settings();
$settings->setAppInfo($appInfo);

foreach ($config["bots"] as $username => $bot) {
    $api = new API("sessions/".$username, $settings);
    $api->botLogin($bot["token"]);
    $bots[] = $api;
}

API::startAndLoopMulti($bots, BotEventHandler::class);
