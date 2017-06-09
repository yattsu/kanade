<?php
$insults = [
	'Ok so this is your harassment.',
	'*Harass.*',
	'You\'re weak.',
	'Give up mate.',
	'Find a purpose in life.',
	'You can\'t do shit.',
	'You wanted harassment? Now you have it.',
	'You heard something? I heard a no one.',
	'-> You :gun:',
	'Git gud scrub.',
	'Ay! Shut up.',
	'No.',
	'Stop. Talking... Please.',
	'Cringe.',
	'Don\'t kill the chat please.',
	'FYI, that was unnecessary.',
	'I know you\'re lonely, but spamming the chat isn\'t the cure.',
	'I don\'t think I can add anything to that.',
	'You\'re doing my job for me right now. Keep it up. :thumbsup:',
	'Stop. Talking... Please. :expressionless:',
	'Ha ha ha. Funny.',
	'That is the stupidest thing I\'ve heard in a while.',
	'Believe in yourself. You are better than this.',
	'That is not something you should say ever again.',
	'People are too nice to tell you to stop.',
	'That\'s not something even I would say.',
	':thinking: ...',
	'Jesus, I\'m glad I\'m not like that.',
	'Can you not? :canunot:',
	'Best damage control now is to stop talking.',
	'No. one. cares.',
	'Do you still have hopes for someone speaking to you?',
	'^ This gave me cancer.',
	'Are you done yet?',
	'Tell me when you\'re done so I can give a fuck again.',
	'I\'m as alive as you are.',
	'You can continue speaking or do us all a favor and shut up.',
	'I really don\'t care.',
	'Why are you even trying?',
	'Oh look. He knows how to write.',
	'Don\'t reply to him or he might think you actually care.',
	'Everybody else, just ignore him.',
	'Don\'t worry, he doesn\'t know what he\'s talking about either.',
	'Now I\'m really confused. But I think you\'re more confused than me.',
	'I was about to say something intelligent to you but... Forget it.',
	'What\'s that? Try to speak properly.',
	':point_left: :poop:',
	'You\'re about as useful as a bidet on a motorcycle.'
];

$random_harass = rand(0, count($insults) - 1);

$victim = [
	'A Wooden Nail'
];

$safe_users = [
	'ambanane',
	'Nyalice'
];

$safe_channels = [
	'introduction'
];

$channel_name = $this->MESSAGE->channel->name;

if (in_array($channel_name, $safe_channels)) {
	return;
}

$chance = rand(1, 100);
$victim_chance = 3;
$regular_chance = 1;

$author_username = $this->MESSAGE->author->username;

if (in_array($author_username, $victim)) {
	if ($chance <= $victim_chance) {
		$this->reply($insults[$random_harass]);
	}
}

if (!in_array($author_username, $safe_users) && !in_array($author_username, $victim) && $author_username !== $this->BOT_USERNAME) {
	if ($chance <= $regular_chance) {
		$this->reply($insults[$random_harass]);
	}
}
?>