<?php
require_once 'model/database.php';
$database = new Database;

$query = 'DELETE FROM hg_match WHERE organizer = "' . $this->MESSAGE->author->username . '"';
$result = $database->MYSQL->query($query);

$query = 'DELETE FROM hg_participants WHERE organizer = "' . $this->MESSAGE->author->username . '"';
$result = $database->MYSQL->query($query);
	
$this->say('All Hunger Games matches have been deleted');
?>