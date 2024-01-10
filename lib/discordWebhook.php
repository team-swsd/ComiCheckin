<?php
require '../vendor/autoload.php';
Dotenv\Dotenv::createImmutable(__DIR__. "/../")->load();

function discordWebhook($discordContent) {
    $discordWebhookURL = $_ENV['DISCORDENDPOINT'];

    $discordJson = json_encode($discordContent);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($ch, CURLOPT_POSTFIELDS, $discordJson);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_URL, $discordWebhookURL);
    $result=curl_exec($ch);
    curl_close($ch);
}


