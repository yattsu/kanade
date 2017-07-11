<?php
require_once 'model/database.php';
$database = new Database;

$message_content = $this->MESSAGE->content;
$author_id = $this->MESSAGE->author->id;
$author_username = $this->MESSAGE->author->username;
$author_discriminator = $this->MESSAGE->author->discriminator;

$bad_list = [
	'fuck' => 1,
	'jerk' => 1,
	'asshole' => 1,
	'hate' => 1,
	'hell' => 1,
	'shit' => 1,
	'dumb' => 1,
	'dick' => 2,
	'ass' => 2,
	'idiot' => 2,
	'stupid' => 2,
	'moron' => 2,
	'boobs' => 2,
	'tits' => 2,
	'pussy' => 2,
	'fag' => 2,
	'bitch' => 3,
	'faggot' => 3,
	'dead' => 3,
	'murder' => 4,
	'suicide' => 4,
	'kill' => 4,
	'gay' => 5,
	'retard' => 5,
	'retarded' => 5
];

$good_list = [
	'luck' => 2,
	'right' => 2,
	'nice' => 2,
	'ok' => 2,
	'hi' => 2,
	'hey' => 2,
	'handsome' => 3,
	'beautiful' => 3,
	'apologize' => 7,
	'sorry' => 7
];

$amount = 0;

$message_exploded = explode(' ', $message_content);

foreach ($message_exploded as $word) {
	$word = strtolower($word);
	
	if (array_key_exists($word, $bad_list)) {
		$amount += $bad_list[$word];
	} 
	// elseif (array_key_exists($word, $good_list)) {
	// 	$amount -= $good_list[$word];
	// }
}

$query = 'SELECT * from users WHERE user_id = ' . $author_id;
$result = $database->MYSQL->query($query);
$rows_number = $result->num_rows;

if ($rows_number == 0) {
	$query = 'INSERT INTO users (username, user_id, discriminator, psychopass) VALUES ("' . $author_username . '", "' . $author_id . '", "' . $author_discriminator . '", "0")';
} else {
	$row = $result->fetch_assoc();
	$psychopass = $row['psychopass'];

	if (($psychopass + $amount) < 0) {
		$query = 'UPDATE users SET psychopass = "0" WHERE user_id = "' . $author_id . '"';
	} else {
		$query = 'UPDATE users SET psychopass = psychopass + "' . $amount . '" WHERE user_id = "' . $author_id . '"';
	}
}

$result = $database->MYSQL->query($query);
?>