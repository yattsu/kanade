<?php
require_once 'model/database.php';
$database = new Database;

date_default_timezone_set('UTC');
$command_argument = $this->command_argument;
$author_id = str_replace('!', '', $this->MESSAGE->author->id);

if (!$command_argument) {
	$query = 'SELECT utc FROM users WHERE user_id = "' . $author_id . '"';
} else {
	if (substr_count($command_argument, '<@') == 1 && substr_count($command_argument, '>') == 1) {
		$start = strpos($command_argument, '<@');
		$end = strpos($command_argument, '>');
		$user_id = str_replace('!', '', substr($command_argument, $start + 2, $end - 2));

		$query = 'SELECT utc FROM users WHERE user_id = "' . $user_id . '"';
	}
}

$result = $database->MYSQL->query($query);
$row = $result->fetch_assoc();

$utc = $row['utc'];

if (substr_count($utc, '+') > 0) {
	$time = date('H:i', time() + (3600 * substr($utc, 1)));
} elseif (substr_count($utc, '-') > 0) {
	$time = date('H:i', time() - (3600 * substr($utc, 1)));
} else{
	$time = null;
}

if (!$time) {
	$this->say('`Set the time with $settime +/- *your UTC offset* (Example: $settime +3)`');
} else {
	$this->say(':clock11: **|** `' . $time . ' (UTC ' . $utc . ')`');
}
?>