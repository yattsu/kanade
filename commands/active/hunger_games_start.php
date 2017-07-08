<?php
require_once 'model/database.php';
$database = new Database;

$organizer = $this->MESSAGE->author->username;

$query = 'SELECT * FROM hg_match';
$result = $database->MYSQL->query($query);
$rows_number = $result->num_rows;
$row = $result->fetch_assoc();

if ($rows_number < 1) {
	$query = 'INSERT INTO hg_match (active, organizer) VALUES (1, "' . $organizer . '")';

	if ($result = $database->MYSQL->query($query)) {
		$this->reply('You created a Hunger Games match. Use `<command>` to add participants');

		return;
	}

	$this->reply('Something went wrong');

	return;
}

if ($row['active'] == 1) {
	$this->reply('A match is already in progress. Use `hgstop` to end it');

	return;
}

$query = 'UPDATE hg_match SET active = 1 WHERE organizer = "' . $organizer . '"';

if ($result = $database->MYSQL->query($query)) {
	$this->reply('You created a Hunger Games match. Use `<command>` to add participants');

	return;
}

$this->reply('Something went wrong');
?>