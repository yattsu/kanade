<?php
require_once 'config.php';
require_once __DIR__ . '/vendor/autoload.php';

$discord = new \Discord\Discord([
	'token' => TOKEN
]);

$discord->on('ready', function ($discord) {
    echo "\n\n>>> " . $discord->user->username . " is functional.\n\n", PHP_EOL;
  
    $discord->on('message', function ($message) {
        echo '<<< [' . $message->author->username . ']: ' . $message->content . "\n";
        require 'events/message.php';
    });
});

$discord->run();
?>