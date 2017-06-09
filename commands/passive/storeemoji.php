<?php
require_once 'model/database.php';
$database = new Database;

$message_content = $this->MESSAGE->content;
$message_exploded = explode(' ', $message_content);
$limit = 0;

foreach($message_exploded as $word) {
	if (strpos($word, '<:') !== false && substr_count($word, ':') == 2 && substr_count($word, '<') == 1 && substr_count($word, '>') == 1) {
		$limit++;

		if ($limit > 2) {
			return;
		}

		$word_exploded = explode(':', $word);
		$emoji_name = $word_exploded[1];
		$emoji_id = str_replace('>', '', $word_exploded[2]);

		$query = 'SELECT * FROM emoji WHERE emoji_id = ' . $emoji_id . ' AND name = "' . $emoji_name . '"';
		$result = $database->MYSQL->query($query);
		$rows_number = $result->num_rows;

		if ($rows_number > 0) {
			$query = 'UPDATE emoji SET times_used = times_used + 1 WHERE emoji_id = ' . $emoji_id;
		} else {
			$query = 'INSERT INTO emoji (name, emoji_id, times_used) VALUES ("' . $emoji_name . '", "' . $emoji_id . '", "' . 1 . '")';
		}

		$result = $database->MYSQL->query($query);
	}
}
?>