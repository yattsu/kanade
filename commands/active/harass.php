<?php
$insults = [
	'You\'re a penus.',
	'You’re a butt... face!',
	'I bet you kiss girls, faggot.',
	'Your mom.',
	'You are not the best person i know!',
	'I bet you stink.',
	'The smartest thing that ever came out of your mouth is a penus.',
	'I\'d slap you, but shit stains.',
	'I keel you.',
	'Is a fag.',
	'Keel yourself :gun:',
	'Has no life.'
];

if (strpos($this->command_argument, $this->MASTER_ID) !== false) {
	$this->say('I would never harass my senpai!');

	return;
}

$random_insult = rand(0, count($insults) - 1);

$this->say($this->command_argument . ' ' . $insults[$random_insult]);
?>