<?php
$message_content = $this->MESSAGE->content;

if (!$message_content) {
	return;
}

require_once 'model/database.php';
$database = new Database;

$author_username = $this->MESSAGE->author->username;
$author_id = $this->MESSAGE->author->id;
$message_id = $this->MESSAGE->id;
$time = date('h:i:s', time());
$date = date('d/m/Y');

$query = 'INSERT INTO messages (content, author_name, author_id, message_id, send_time, send_date) VALUES ("' . $message_content . '", "' . $author_username . '", "' . $author_id . '", "' . $message_id . '", "' . $time . '", "' . $date . '")';
$result = $database->MYSQL->query($query);
?>