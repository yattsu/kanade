<?php
require_once 'model/database.php';
$database = new Database;

$organizer = $this->MESSAGE->author->username;

$query = 'SELECT active FROM hg_match WHERE organizer = "' . $organizer . '"';
$result = $database->MYSQL->query($query);
$rows_number = $result->num_rows;
$row = $result->fetch_assoc();

if ($rows_number < 1 || $row['active'] == 0) {
	$this->reply('No active match. Use `hgstart` to create one');

	return;
}

$command_arguments = explode(' ', $this->MESSAGE->content);
array_shift($command_arguments);

$participants = implode(' ', $command_arguments);
$participants = explode(',', $participants);
var_dump($participants);

foreach ($participants as $key => $value) {
	if (empty($value)) {
		unset($participants[$key]);
	}
}

if (empty($participants)) {
	$this->say('You must add at least one participant');

	return;
}

$query = 'SELECT participants_number FROM hg_match WHERE organizer = "' . $organizer . '"';
$result = $database->MYSQL->query($query);
$row = $result->fetch_assoc();

if (($row['participants_number'] + count($participants)) > 24) {
	$this->say('A maximum of 24 participants is allowed');

	return;
}

foreach ($participants as $participant) {
	if (empty(trim($participant))) {
		continue;
	}

	$query = 'SELECT district FROM hg_participants ORDER BY district DESC LIMIT 1';
	$result = $database->MYSQL->query($query);
	$row = $result->fetch_assoc();

	$query = 'SELECT district FROM hg_participants WHERE district = "' . $row['district'] . '"';
	$result = $database->MYSQL->query($query);
	$rows_number = $result->num_rows;

	if ($rows_number == 0) {
		$district = 1;
	} elseif ($rows_number < 2) {
		$district = $row['district'];
	} else {
		$district = $row['district'] + 1;
	}

	$name = trim($participant);
	$query = 'INSERT INTO hg_participants (name, district, organizer) VALUES ("' . $name . '", "' . $district . '", "' . $organizer . '")';
	$result = $database->MYSQL->query($query);

	$query = 'UPDATE hg_match SET participants_number = participants_number + 1 WHERE organizer = "' . $organizer . '"';
	$result = $database->MYSQL->query($query);
}

	$this->say('The participants have been added');
?>