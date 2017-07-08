<?php
require_once 'model/database.php';
$database = new Database;

$organizer = $this->MESSAGE->author->username;

$query = 'SELECT active FROM hg_match WHERE organizer = "' . $organizer . '"';
$result = $database->MYSQL->query($query);
$rows_number = $result->num_rows;
$row = $result->fetch_assoc();

if ($row['active'] == 0 || $rows_number < 1) {
	$this->reply('No active match found. Use `hgstart` to create one');

	return;
}

$query = 'SELECT participants_number FROM hg_match';
$result = $database->MYSQL->query($query);
$row = $result->fetch_assoc();

if ($row['participants_number'] < 2) {
	$this->reply('Not enough participants for the match. Use `hgadd` to add them');

	return;
}

$participants = [];
$turn_results = [];

$weapons = [
	0 => [
		'id' => 0,
		'name' => 'sword',
		'chance' => 85,
		'ranged' => 0
	],
	1 => [
		'id' => 1,
		'name' => 'staff',
		'chance' => 55,
		'ranged' => 0
	],
	2 => [
		'id' => 2,
		'name' => 'knife',
		'chance' => 70,
		'ranged' => 0
	],
	3 => [
		'id' => 3,
		'name' => 'rope',
		'chance' => 25,
		'ranged' => 0
	]
];

$weapon_fatalities = [
	0 => [
		'Brutally killed',
		'Swingy swingy'
	],
	1 => [
		'Crushed the head of',
		'Beat to death'
	]
];

$query = 'SELECT * FROM hg_participants';
$result = $database->MYSQL->query($query);
while ($row = $result->fetch_assoc()) {
	$participants[] = $row;
}

foreach ($participants as $player) {
	$player_id = $player['id'];
	$player_name = $player['name'];
	$player_weapon = $player['weapon'] !== null ? $weapons[$player['weapon']] : $player['weapon'];
	$player_item = $player['item'];
	$player_friends = explode(',', $player['friends']);
	$player_dead = $player['dead'];
	$player_busy = $player['busy'];
	$player_kills = $player['kills'];

	if ($player_dead == 1 || $player_busy == 1) {
		continue;
	}

	$potential_targets = [];

	foreach ($participants as $target) {
		if ($target['id'] !== $player_id && $target['busy'] == 0 && $target['dead'] == 0) {
			$potential_targets[] = $target;
		}
	}

	$target_chance = rand(0, 100);
	$target = false;
	$target_dead = false;
	$target_poisoned = false;
	$target_starving = false;
	$target_freezing = false;

	if ($target_chance > 70 && !empty($potential_targets)) {
		$target = $potential_targets[rand(0, count($potential_targets) - 1)];
	}

	if ($target !== false) {
		// foreach ($participants as &$participant) {
		// 	if ($participant['id'] == $target['id']) {
				$target['busy'] = 1;

				$target_id = $target['id'];
				$target_name = $target['name'];
				$target_weapon = $target['weapon'] !== null ? $weapons[$target['weapon']] : $target['weapon'];
				$target_item = $target['item'];
				$target_friends = explode(',', $target['friends']);
				$target_dead = $target['dead'];
				$target_busy = $target['busy'];
		// 	}
		// }
	}

	if ($player_weapon == null) {
		$weapon_chance = rand(0, 100);

		if ($weapon_chance > 70) {
			$random_weapon = rand(0, count($weapons) - 1);

			$player_weapon = $weapons[$random_weapon];

			$query = 'UPDATE hg_participants SET weapon = "' . $random_weapon . '" WHERE id = "' . $player_id . '"';
			$result = $database->MYSQL->query($query);
		}
	} else {
		if ($target !== false) {
			if ($player_weapon['ranged'] == 1) {
				if (rand(0, 100) < $player_weapon['chance']) {
					$target_dead == true;
				}
			} else {
				if ($target_weapon == 0) {
					$escape_chance = rand(0, 100);

					if ($escape_chance > 90) {
						$target_escaped = true;
					} else {
						$target_dead = true;
						$turn_results[] = $player_name . ' ' . $weapon_fatalities[$player_weapon['id']][rand(0, count($weapon_fatalities) - 1)] . ' ' . $target_name . "\n";
					}
				} else {
					$kill_chance = rand(0, 100);

					if ($player_weapon['chance'] >= $kill_chance) {
						$target_dead = true;
						$turn_results[] = $player_name . ' ' . $weapon_fatalities[$player_weapon['id']][rand(0, count($weapon_fatalities) - 1)] . ' ' . $target_name . "\n";
					} elseif ($target_weapon['chance'] >= $kill_chance) {
						$player_dead = true;
						$turn_results[] = $target_name . ' ' . $weapon_fatalities[$target_weapon['id']][rand(0, count($weapon_fatalities) - 1)] . ' ' . $player_name . "\n";
					}
				}
			}
		}
	}

	if ($player_dead == true) {
		$query = 'UPDATE hg_participants SET dead = 1 WHERE id = "' . $player_id . '"';
		$result = $database->MYSQL->query($query);
	} elseif ($target_dead == true) {
		$query = 'UPDATE hg_participants SET dead = 1 WHERE id = "' . $target_id . '"';
		$result = $database->MYSQL->query($query);

		$query = 'UPDATE hg_participants SET kills = kills + 1 WHERE id = "' . $player_id . '"';
		$result = $database->MYSQL->query($query);
	}
}

var_dump($turn_results);
?>