<?php
require_once 'model/database.php';
$database = new Database;

$leaderboard = [];
$guild_id = $this->MESSAGE->channel->guild->id;

$query = 'SELECT * FROM nations WHERE guild_id = "' . $guild_id . '" ORDER BY points DESC';
$result = $database->MYSQL->query($query);
while ($row = $result->fetch_assoc()) {
	$leaderboard[] = [
		'name' => $row['name'],
		'id' => $row['nation_id'],
		'points' => $row['points']
	];
}

$leaderboard_message = "```prolog
Nation Leaderboard \n\n";
$index = 0;

foreach ($leaderboard as $nation) {
	$index++;
	$leaderboard_message .= $index . '. ' . ucfirst($nation['name']) . ' nation with ' . $nation['points'] . ' points' . "\n";
}

$leaderboard_message .= '```';

$this->say($leaderboard_message);
?>