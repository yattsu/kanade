<?php
$gifs = [
'http://gph.is/23X7fZF',
'http://gph.is/XLWs5q',
'http://gph.is/1PwuvoE',
'http://gph.is/1MbFkKS'
];

$random_gif = rand(0, count($gifs) - 1);

$author_username = $this->MESSAGE->author->username;
$command_argument = $this->command_argument;

$this->say('**' . $author_username . '** kicked **' . $command_argument . "**\n" . $gifs[$random_gif]);
?>