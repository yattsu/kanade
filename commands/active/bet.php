<?php
require_once 'model/database.php';
$database = new Database;

$command_argument = $this->command_argument;
$bet_amount = explode(' ', $this->MESSAGE->content);
$bet_amount = isset($bet_amount[2]) ? trim($bet_amount[2]) : false;
$author_id = $this->MESSAGE->author->id;

if (!substr_count($command_argument, '<@') || !substr_count($command_argument, '>')) {
	$this->reply('The bet master must be a valid user');

	return;
}

if (!is_numeric($bet_amount)) {
	$this->reply('You must bet a valid number or schmeckles');

	return;
}

if ($bet_amount < 1) {
	$this->reply('You can\'t bet a negative amount of schmeckles');

	return;
}

$query = 'SELECT amount FROM schmeckles WHERE user_id = "' . $author_id . '"';
$result = $database->MYSQL->query($query);
$rows_number = $result->num_rows;
$row = $result->fetch_assoc();
$amount = $row['amount'];

if ($rows_number < 1) {
	$this->reply('You don\'t have a schmeckles account');

	return;
}

if ($amount < $bet_amount) {
	$this->reply('You don\'t have enough schmeckles');

	return;
}

$start = strpos($command_argument, '<@');
$end = strpos($command_argument, '>');
$mention_id = str_replace('!', '', substr($command_argument, $start + 2, $end - 2));

$query = 'SELECT master_id FROM bets WHERE master_id = "' . $mention_id . '"';
$result = $database->MYSQL->query($query);
$num_rows = $result->num_rows;

if ($num_rows < 1) {
	$this->reply('The user doesn\'t host a bet at the moment');

	return;
}

$query = 'SELECT * FROM bets WHERE master_id = "' . $mention_id . '" AND participant_id IS NULL';
$result = $database->MYSQL->query($query);
$rows_number = $result->num_rows;
$row = $result->fetch_assoc();
$master_amount = $row['master_amount'];
$master_id = $row['master_id'];

if ($rows_number > 0 && $author_id == $master_id) {
	$query = 'UPDATE bets SET master_amount = master_amount + "' . $bet_amount . '" WHERE master_id = "' . $mention_id . '" AND participant_id IS NULL';

	if (!$result = $database->MYSQL->query($query)) {
		$this->reply('Something went wrong. The bet was cancelled');

		return;
	}

	$query = 'UPDATE schmeckles SET amount = amount - "' . $bet_amount . '" WHERE user_id = "' . $author_id . '"';
	$result = $database->MYSQL->query($query);

	$this->reply('You raised your minimum bet amount to **' . ($master_amount + $bet_amount) . '** schmeckles');

	return;
}

$query = 'SELECT master_amount FROM bets WHERE master_id = "' . $mention_id . '"';
$result = $database->MYSQL->query($query);
$row = $result->fetch_assoc();
$min_amount = $row['master_amount'];

if ($bet_amount < $min_amount) {
	$this->reply('The minimum bet amount is **' . $min_amount . '** schmeckles');

	return;
}

$query = 'UPDATE schmeckles SET amount = amount - "' . $bet_amount . '" WHERE user_id = "' . $author_id . '"';
$result = $database->MYSQL->query($query);

$query = 'SELECT master_id, participant_id FROM bets WHERE master_id = "' . $mention_id . '" AND participant_id = "' . $author_id . '"';
$result = $database->MYSQL->query($query);
$rows_number = $result->num_rows;

if ($rows_number > 0) {
	$query = 'UPDATE bets SET participant_amount = participant_amount + "' . $bet_amount . '" WHERE participant_id = "' . $author_id . '" AND master_id = "' . $mention_id . '"';

	if (!$result = $database->MYSQL->query($query)) {
		$this->reply('Something went wrong. The bet was cancelled');

		return;
	}

	$this->reply('You successfully added **' . $bet_amount . '** schmeckles to your current amount on <@' . $mention_id . '>\'s bet');
} else {
	$query = 'INSERT INTO bets (master_id, participant_id, participant_amount) VALUES ("' . $mention_id . '", "' . $author_id . '", "' . $bet_amount . '")';

	if (!$result = $database->MYSQL->query($query)) {
		$this->reply('Something went wrong. The bet was cancelled');

		return;
	}

	$this->reply('You entered <@' . $mention_id . '>\'s bet with the amount of **' . $bet_amount . '** schmeckles');
}
?>