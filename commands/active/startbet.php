<?php
require_once 'model/database.php';
$database = new Database;

$command_argument = $this->command_argument;
$author_id = $this->MESSAGE->author->id;

if (!is_numeric($command_argument)) {
	$this->reply('You must bet a valid number or schmeckles');

	return;
}

if ($command_argument < 1) {
	$this->reply('You can\'t start bet with a negative amount of schmeckles');

	return;
}

$query = 'SELECT amount FROM schmeckles WHERE user_id = "' . $author_id . '"';
$result = $database->MYSQL->query($query);
$rows_number = $result->num_rows;
$row = $result->fetch_assoc();
$amount = $row['amount'];

if ($rows_number < 1) {
	$this->reply('You don\'t have a schmeckles account yet. Create it by typing `$daily`');

	return;
}

if ($command_argument > $amount) {
	$this->reply('You don\'t have enough schmeckles');

	return;
}

$query = 'SELECT master_id FROM bets WHERE master_id = "' . $author_id . '"';
$result = $database->MYSQL->query($query);
$rows_number = $result->num_rows;

if ($rows_number > 0) {
	$this->reply('You already host a bet. Type `$betresults` to end it');

	return;
}

$query = 'UPDATE schmeckles SET amount = amount - "' . $command_argument . '" WHERE user_id = "' . $author_id . '"';
$result = $database->MYSQL->query($query);

$query = 'INSERT INTO bets (master_id, master_amount) VALUES ("' . $author_id . '", "' . $command_argument . '")';
if (!$result = $database->MYSQL->query($query)) {
	$this->reply('Something went wrong. The bet was not started');

	return;
}

$this->reply('You started a bet with the minimum amount of **' . $command_argument . '** schmeckles' . "\n");
$this->say('Everyone can enter <@' . $author_id . '>\'s bet by typing `$bet` <@' . $author_id . '> *`schmeckles amount`*');
?>