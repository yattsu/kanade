<?php
require_once 'model/database.php';
$database = new Database;

$daily_amount = 325;
$command_argument = $this->command_argument;
$author_id = $this->MESSAGE->author->id;
$time_now = time();

$query = 'SELECT last_daily FROM schmeckles WHERE user_id = "' . $author_id . '"';
$result = $database->MYSQL->query($query);
$row = $result->fetch_assoc();

$last_daily = $row['last_daily'];
$wait = 24 * 3600;

if (($time_now - $last_daily) < $wait) {
	$this->reply('You need to wait at least 24h before using this command again');

	return;
}

if ((substr_count($command_argument, '<@') || substr_count($command_argument, '<!')) && substr_count($command_argument, '>')) {
	$start = strpos($command_argument, '<@');
	$end = strpos($command_argument, '>');
	$mention_id = str_replace('!', '', substr($command_argument, $start + 2, $end - 2));

	$query = 'SELECT user_id FROM schmeckles WHERE user_id = "' . $mention_id . '"';
	$result = $database->MYSQL->query($query);
	$rows_number = $result->num_rows;

	if ($rows_number < 1) {
		$query = 'INSERT INTO schmeckles (user_id, amount) VALUES ("' . $mention_id . '", "'. $daily_amount .'")';
	} else {
		$query = 'UPDATE schmeckles SET amount = amount + "' . $daily_amount . '" WHERE user_id = "' . $mention_id . '"';
	}

	if (!$result = $database->MYSQL->query($query)) {
		$this->reply('Something went wrong');

		return;
	}

	$query = 'UPDATE schmeckles SET last_daily = "' . $time_now . '" WHERE user_id = "' . $author_id . '"';
	$result = $database->MYSQL->query($query);

	$query = 'SELECT user_id FROM schmeckles WHERE user_id = "' . $author_id . '"';
	$result = $database->MYSQL->query($query);
	$rows_number = $result->num_rows;

	if ($rows_number < 1) {
		$query = 'INSERT INTO schmeckles (user_id, last_daily) VALUES ("' . $author_id . '", "' . time() . '")';
		$result = $database->MYSQL->query($query);
	}

	$this->reply('You gave your daily schmeckles to <@' . $mention_id . '>');

	return;
}

$query = 'SELECT user_id FROM schmeckles WHERE user_id = "' . $author_id . '"';
$result = $database->MYSQL->query($query);
$rows_number = $result->num_rows;

if ($rows_number < 1) {
	$query = 'INSERT INTO schmeckles (user_id, amount, last_daily) VALUES ("' . $author_id . '", "' . $daily_amount . '", "' . $time_now . '")';
} else {
	$query = 'UPDATE schmeckles SET amount = amount + "' . $daily_amount . '", last_daily = "' . $time_now . '" WHERE user_id = "' . $author_id . '"';
}

if (!$result = $database->MYSQL->query($query)) {
	$this->reply('Something went wrong');

	return;
}

$this->reply('You claimed your **' . $daily_amount . '** schmeckles');
?>