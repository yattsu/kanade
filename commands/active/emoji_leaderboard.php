<?php
require_once 'model/database.php';
$database = new Database;

$query = 'SELECT * FROM emoji ORDER BY times_used DESC LIMIT 5';
$result = $database->MYSQL->query($query);

$leaderboard = [];

while ($row = $result->fetch_assoc()) {
	$leaderboard[] = [
		'name' => $row['name'],
		'id' => $row['emoji_id'],
		'times_used' => $row['times_used']
	];
}

$leaderboard_message = "__**`Emoji Leaderboard`**__ \n" . '`';
$index = 0;

foreach ($leaderboard as $emoji) {
	$index++;
	$leaderboard_message .= $index . '. `<:' . $emoji['name'] . ':' . $emoji['id'] . '>` - ' . $emoji['times_used'] . ' times' . "\n";
}

$leaderboard_message .= '`';

$this->say($leaderboard_message);
?>