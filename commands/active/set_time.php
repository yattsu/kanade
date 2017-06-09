<?php
$command_argument = $this->command_argument;

if (!$command_argument) {
	return;
}

if (!(substr_count($command_argument, '+') || substr_count($command_argument, '-')) || strlen($command_argument) > 3 || !is_numeric(substr($command_argument, 1))) {
	$this->say('`Invalid time`');

	return;
} elseif (substr($command_argument, 1) > 14) {
	$this->say('`You can\'t set a difference bigger than 14`');

	return;
}

require_once 'model/database.php';
$database = new Database;

$user_id = $this->MESSAGE->author->id;

$query = 'UPDATE users SET utc = "' . $command_argument . '" WHERE user_id = ' . str_replace('!', '', $user_id);
$result = $database->MYSQL->query($query);
?>