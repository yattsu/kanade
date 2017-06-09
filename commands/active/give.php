<?php
require_once 'model/database.php';
$database = new Database;

$command_argument = $this->command_argument;
$give_amount = explode(' ', $this->MESSAGE->content);
$give_amount = isset($give_amount[2]) ? trim($give_amount[2]) : false;
$author_id = $this->MESSAGE->author->id;

if (!$command_argument) {
	return;
}

if (!$give_amount) {
	return;
}

if (!is_numeric($give_amount)) {
	$this->reply('Only `[0-9]` characters are allowed');

	return;
}

if ((substr_count($command_argument, '<@') || substr_count($command_argument, '<!')) && substr_count($command_argument, '>')) {
	$start = strpos($command_argument, '<@');
	$end = strpos($command_argument, '>');
	$mention_id = str_replace('!', '', substr($command_argument, $start + 2, $end - 2));

	if ($give_amount < 1) {
		$this->reply('You can\'t give a negative amount of schmeckles');

		return;
	}

	if ($author_id == $mention_id) {
		$this->reply('You can\'t give yourself schmeckles. That\'s both wrong and sad');

		return;
	}

	$query = 'SELECT amount FROM schmeckles WHERE user_id = "' . $author_id . '"';

	$result = $database->MYSQL->query($query);
	$rows_number = $result->num_rows;

	if ($rows_number < 1) {
		$this->reply('You don\'t have a schmeckles account yet. Create it by typing `$daily`');

		return;
	}

	$row = $result->fetch_assoc();
	$amount = $row['amount'];

	if ($amount < $give_amount) {
		$this->reply('You don\'t have enough schmeckles');

		return;
	}

	$query = 'UPDATE schmeckles SET amount = amount - "' . $give_amount . '" WHERE user_id = "' . $author_id . '"';
	$result = $database->MYSQL->query($query);

	$query = 'SELECT user_id FROM schmeckles WHERE user_id = "' . $mention_id . '"';
	$result = $database->MYSQL->query($query);
	$rows_number = $result->num_rows;

	if ($rows_number < 1) {
		$query = 'INSERT INTO schmeckles (user_id, amount) VALUES ("' . $mention_id . '", "' . $give_amount . '")';
	} else {
		$query = 'UPDATE schmeckles SET amount = amount + "' . $give_amount . '" WHERE user_id = "' . $mention_id . '"';
	}

	if (!$result = $database->MYSQL->query($query)) {
		$this->reply('Something went wrong');

		return;
	}

	$this->reply('You gave <@' . $mention_id . '> **' . $give_amount . '** schmeckles');
}
?>