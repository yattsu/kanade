<?php
require_once 'model/database.php';
$database = new Database;

$author_id = str_replace('!', '', $this->MESSAGE->author->id);
$author_name = $this->MESSAGE->author->username;

$query = 'UPDATE users SET afk = "yes" WHERE user_id = "' . $author_id . '"';
$result = $database->MYSQL->query($query);

$this->say('**`[AFK mode]: ON, ' . $author_name . '`**');
?>