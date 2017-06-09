<?php
$compliments = [
	'You\'re a qtpie.',
	'You\'re niceu.',
	'You\'re my best friendo.',
	'I like the way you look today.',
	'I would date you.',
	'I like your face!',
	'You are the nicest person on this server.',
	'What a cutie.',
	'Aren\'t you the most beautiful thing in the world?',
	'I <3 you. (pls no flirting)',
	'Who\'s handsome? You\'re handsome!',
	'I could talk to you all day but the others are getting jealous.'
];

$random_compliment = rand(0, count($compliments) - 1);

$command_argument = $this->command_argument;

$this->say($command_argument . ' ' . $compliments[$random_compliment]);
?>