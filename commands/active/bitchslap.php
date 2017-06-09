<?php
$gifs = [
'http://gph.is/16Q8alO',
'http://gph.is/14innny',
'http://gph.is/1PlcayB',
'http://gph.is/10HiqXX'
];

$random_gif = rand(0, count($gifs) - 1);

$author_username = $this->MESSAGE->author->username;
$command_argument = $this->command_argument;

$this->say('**' . $author_username . '** bitch slapped **' . $command_argument . "**\n" . $gifs[$random_gif]);
?>