<?php
require_once 'model/database.php';
$database = new Database;

$keyword = 'blob';

if ($this->MESSAGE->author->user->bot == null) {
	if ($this->MESSAGE->author->id !== '170969285110267904') {
		foreach ($this->MESSAGE->author->roles as $role) {
			if (substr_count($role['name'], $keyword)) {
				$nation_name = trim(str_replace($keyword, '', $role['name']));
				$nation_id = $role['id'];
			}
		}

		if (isset($nation_name)) {
			$query = 'SELECT * FROM nations WHERE nation_id = "' . $nation_id . '"';
			$result = $database->MYSQL->query($query);
			$rows_number = $result->num_rows;

			$guild_id = $this->MESSAGE->channel->guild->id;

			if ($rows_number < 1) {
				$query = 'INSERT INTO nations (nation_id, name, points, guild_id) VALUES ("' . $nation_id . '", "' . $nation_name . '", 1, "' . $guild_id . '")';
				echo 'FIRST ' . $nation_name . "\n";
			} else {
				$query = 'UPDATE nations SET points = points + 1 WHERE nation_id = "' . $nation_id . '" AND guild_id = "' . $guild_id . '"';
				echo '+1 ' . $nation_name . "\n";
			}
			$result = $database->MYSQL->query($query);
		}
	} else {
		$nation_name = 'black';
		$nation_id = '330797449629270017';

		if (isset($nation_name)) {
			$query = 'SELECT * FROM nations WHERE nation_id = "' . $nation_id . '"';
			$result = $database->MYSQL->query($query);
			$rows_number = $result->num_rows;

			$guild_id = $this->MESSAGE->channel->guild->id;

			if ($rows_number < 1) {
				$query = 'INSERT INTO nations (nation_id, name, points, guild_id) VALUES ("' . $nation_id . '", "' . $nation_name . '", 1, "' . $guild_id . '")';
				echo 'FIRST ' . $nation_name . "\n";
			} else {
				$query = 'UPDATE nations SET points = points + 1 WHERE nation_id = "' . $nation_id . '" AND guild_id = "' . $guild_id . '"';
				echo '+1 ' . $nation_name . "\n";
			}
			$result = $database->MYSQL->query($query);
		}
	}
}
?>