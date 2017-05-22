<?php
require_once __DIR__ . '/vendor/autoload.php';

$discord = new \Discord\Discord([
	'token' => 'YOUR AUTH KEY'
]);

$discord->on('ready', function ($discord) {
    echo "\n\n>>> Banan is up and running, sir!.\n\n", PHP_EOL;
  
    // Listen for events here
    $discord->on('message', function ($message) {
        echo '>>> [' . $message->author->username . ']: ' . $message->content . "\n\n";
        require 'events/message.php';
    });
});

$discord->run();
?>