<?php
require_once 'model/database.php';
$database = new Database;

$message_content = $this->MESSAGE->content;

if (!substr_count($message_content, '<@') || !substr_count($message_content, '>')) {
	return;
}

$mentions = explode(' ', $message_content);
$verdict = '';

foreach ($mentions as $mention) {
	if (!substr_count($mention, '<@') || !substr_count($mention, '>')) {
		continue;
	}

	$start = strpos($mention, '<@');
	$end = strpos($mention, '>');
	$user_id = str_replace('!', '', substr($mention, $start + 2, $end - 2));

	$query = 'SELECT afk FROM users WHERE user_id = "' . $user_id . '"';
	$result = $database->MYSQL->query($query);
	$row = $result->fetch_assoc();

	if ($row['afk'] == 'yes') {
		$query = 'SELECT username FROM users WHERE user_id = "' . $user_id . '"';
		$result = $database->MYSQL->query($query);
		$row = $result->fetch_assoc();

		$verdict .= "**`" . $row['username'] . " is AFK`**\n";
	}
}

$this->say($verdict);
?>