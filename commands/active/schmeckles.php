<?php
require_once 'model/database.php';
$database = new Database;

$command_argument = $this->command_argument;
$author_id = $this->MESSAGE->author->id;

if ((substr_count($command_argument, '<@') || substr_count($command_argument, '<!')) && substr_count($command_argument, '>')) {
	$start = strpos($command_argument, '<@');
	$end = strpos($command_argument, '>');
	$mention_id = str_replace('!', '', substr($command_argument, $start + 2, $end - 2));

	$query = 'SELECT amount FROM schmeckles WHERE user_id = "' . $mention_id . '"';
	$result = $database->MYSQL->query($query);
	$rows_number = $result->num_rows;
	$row = $result->fetch_assoc();
	$amount = $row['amount'];

	$query = 'SELECT username FROM users WHERE user_id = "' . $mention_id . '"';
	$result = $database->MYSQL->query($query);
	$row = $result->fetch_assoc();
	$mention_username = $row['username'];

	if ($rows_number < 1) {
		$this->reply('**' . $mention_username . '** doesn\'t have a schmeckles account');
	} else {
		$this->reply('**' . $mention_username . '** has **' . $amount . '** schmeckles');
	}

	return;
}

$query = 'SELECT amount FROM schmeckles WHERE user_id = "' . $author_id . '"';
$result = $database->MYSQL->query($query);
$rows_number = $result->num_rows;
$row = $result->fetch_assoc();
$amount = $row['amount'];

if ($rows_number < 1) {
	$this->reply('You don\'t have a schmeckles account yet. Create it by typing `$daily`');
} else {
	$this->reply('You have **' . $amount . '** schmeckles');
}
?>