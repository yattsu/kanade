<?php
$command_argument = $this->command_argument;
$message_content = $this->MESSAGE->content;
$author_username = $this->MESSAGE->author->username;

if (!$command_argument) {
	$this->say('I\'m not broken so you did something wrong.');

	return;
} elseif (!strpos($message_content, '?')) {
	$this->say('8ball needs a question, dummy.');

	return;
}

$random = rand(1, 2);

$yes_list = [
	'Yep',
	'Definitely yes',
	'I think yes',
	'I\'m 100% sure',
	'Of course',
	'Sure thing',
	':white_check_mark:'
];

$yes_random = rand(0, count($yes_list) - 1);

$no_list = [
	'Nop',
	'Hell no',
	'This will never happen',
	'How can you even ask that? Of course not',
	'Sorry but no',
	'Negative',
	':no_entry:'
];

$no_random = rand(0, count($no_list) - 1);

if ($random == 1) {
	$this->say(':8ball: | ' . $yes_list[$yes_random] . ', **' . $author_username . '**.');
} else {
	$this->say(':8ball: | ' . $no_list[$no_random] . ', **' . $author_username . '**.');
}
?>