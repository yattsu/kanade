<?php
require_once 'model/database.php';
$database = new Database;

$author_id = $this->MESSAGE->author->id;

$query = 'SELECT master_id FROM bets WHERE master_id = "' . $author_id . '"';
$result = $database->MYSQL->query($query);
$rows_number = $result->num_rows;

if ($rows_number < 1) {
	$this->reply('You don\'t host any bet at the moment');

	return;
}

$query = 'SELECT master_amount FROM bets WHERE master_id = "' . $author_id . '" AND participant_id IS NULL';
$result = $database->MYSQL->query($query);
$row = $result->fetch_assoc();
$master_amount = $row['master_amount'];

$participant_list = [
	[
	'id' => $author_id,
	'amount' => $master_amount
	]
];

$query = 'SELECT participant_id, participant_amount FROM bets WHERE master_id = "' . $author_id . '" AND participant_id IS NOT NULL';
$result = $database->MYSQL->query($query);

while ($row = $result->fetch_assoc()) {
	$participant_list[] = [
		'id' => $row['participant_id'],
		'amount' => $row['participant_amount']
		];
}

$name_list = [];

foreach ($participant_list as $participant) {
	$query = 'SELECT username FROM users WHERE user_id = "' . $participant['id'] . '"';
	$result = $database->MYSQL->query($query);
	$rows_number = $result->num_rows;

	if ($rows_number > 0) {
		$row = $result->fetch_assoc();
		$username = $row['username'];
		$name_list[] = [
			'username' => $username,
			'amount' => $participant['amount']
			];
	}
}

if (count($name_list) < 2) {
	$query = 'DELETE FROM bets WHERE master_id = "' . $author_id . '"';
	$result = $database->MYSQL->query($query);

	$query = 'UPDATE schmeckles SET amount = amount + "' . $master_amount . '" WHERE user_id = "' . $author_id . '"';
	$result = $database->MYSQL->query($query);

	$this->reply('<@' . $author_id . '>\'s bet ended with no participants. The schmeckles were restored');

	return;
}

$prize = 0;

foreach ($name_list as $item) {
	$prize += $item['amount'];
}

$winner_random = rand(0, count($name_list) - 1);
$winner_id = $participant_list[$winner_random]['id'];
$winner_name = $name_list[$winner_random]['username'];

$query = 'UPDATE schmeckles SET amount = amount + "' . $prize . '" WHERE user_id = "' . $winner_id . '"';
$result = $database->MYSQL->query($query);

$query = 'DELETE FROM bets WHERE master_id = "' . $author_id . '"';
$result = $database->MYSQL->query($query);

$this->say('<@' . $author_id . '>\'s bet ended. The winner with a **' . $prize . '** schmeckles prize is <@' . $winner_id . '>!');
?>