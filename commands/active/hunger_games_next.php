<?php
# Require database.php and creating a new object of the Database object
require_once 'model/database.php';
$database = new Database;

# Gets the username of the message author
$organizer = $this->MESSAGE->author->username;

# Select data from the database where the organizer is the message author
$query = 'SELECT active FROM hg_match WHERE organizer = "' . $organizer . '"';
# Executes the query
$result = $database->MYSQL->query($query);
# Counts how many rows have been selected
$rows_number = $result->num_rows;
# Fetch the data
$row = $result->fetch_assoc();

# If 'active' collumn is 0 (which means the match is inactive) OR there's no data at all, message and return
if ($row['active'] == 0 || $rows_number < 1) {
	$this->reply('No active match found. Use `hgstart` to create one');

	return;
}

# Select the 'participants_number' collumn from the 'hg_match' table
$query = 'SELECT participants_number FROM hg_match';
# Executes the query
$result = $database->MYSQL->query($query);
# Fetch the data
$row = $result->fetch_assoc();

# If the value of the 'participants_number' collumn is less than 2, message and return
if ($row['participants_number'] < 2) {
	$this->reply('Not enough participants for the match. Use `hgadd` to add them');

	return;
}

# Select the rows from 'hg_participants' table where the player is dead and the cannon was not heard (it works, both in my head and irl)
$query = 'SELECT * FROM hg_participants WHERE dead = 1 AND cannon = 0';
# Executes the query
$result = $database->MYSQL->query($query);
# Counts how many rows have been selected
$rows_number = $result->num_rows;

# If at least one row is selected (exists)
if ($rows_number > 0) {
	$cannons = [];

	# Fills the $cannons array with each player's data that is dead, AND the cannon shot message was not displayed
	while ($row = $result->fetch_assoc()) {
		$cannons[] = [
			'id' => $row['id'],
			'name' => $row['name'],
			'district' => $row['district']
		];
	}

	# For each dead player, sets 'cannon' collumn to 1 so it will be ignored next time
	foreach ($cannons as $participant) {
		$query = 'UPDATE hg_participants SET cannon = 1 WHERE id = "' . $participant['id'] . '"';
		$result = $database->MYSQL->query($query);
	}

	# The beginning of the message that will be displayed in the end of each turn
	$turn_results = '```css' . "\n" . '#' . count($cannons) . ' cannon shots' . "\n\n";

	# Concatenate data for each dead player to the above string
	foreach ($cannons as $participant) {
		$turn_results .= '[' . $participant['name'] . '] from district #' . $participant['district'] . "\n";

	}

	# Concatenate to the end of the message '```' for discord's sake
	$turn_results .= '```';

	# Sends the message to the chat
	$this->say($turn_results);

	return;
}

# Associative array with each weapon and its properties
$weapons = [
	0 => [
		'id' => 0,
		'name' => 'sword',
		'chance' => 80,
		'ranged' => 0,
		'imobilize' => 0,
		'poison' => 0
	],
	1 => [
		'id' => 1,
		'name' => 'staff',
		'chance' => 55,
		'ranged' => 0,
		'imobilize' => 0,
		'poison' => 0
	],
	2 => [
		'id' => 2,
		'name' => 'knife',
		'chance' => 65,
		'ranged' => 0,
		'imobilize' => 0,
		'poison' => 0
	],
	3 => [
		'id' => 3,
		'name' => 'rope',
		'chance' => 25,
		'ranged' => 0,
		'imobilize' => 1,
		'poison' => 0
	],
	4 => [
		'id' => 4,
		'name' => 'bow',
		'chance' => 15,
		'ranged' => 1,
		'imobilize' => 0,
		'poison' => 0
	],
	5 => [
		'id' => 5,
		'name' => 'crossbow',
		'chance' => 20,
		'ranged' => 1,
		'imobilize' => 0,
		'poison' => 0
	],
	6 => [
		'id' => 6,
		'name' => 'hatchet',
		'chance' => 70,
		'ranged' => 0,
		'imobilize' => 0,
		'poison' => 0
	],
	7 => [
		'id' => 7,
		'name' => 'fork',
		'chance' => 5,
		'ranged' => 0,
		'imobilize' => 0,
		'poison' => 0
	],
	8 => [
		'id' => 8,
		'name' => 'baseball bat',
		'chance' => 35,
		'ranged' => 0,
		'imobilize' => 0,
		'poison' => 0
	],
	9 => [
		'id' => 9,
		'name' => '9mm pistol',
		'chance' => 75,
		'ranged' => 1,
		'imobilize' => 0,
		'poison' => 0
	],
	10 => [
		'id' => 10,
		'name' => '.357 magnum',
		'chance' => 80,
		'ranged' => 1,
		'imobilize' => 0,
		'poison' => 0
	],
	11 => [
		'id' => 11,
		'name' => 'stun baton',
		'chance' => 45,
		'ranged' => 0,
		'imobilize' => 0,
		'poison' => 0
	],
	12 => [
		'id' => 12,
		'name' => 'boomerang',
		'chance' => 10,
		'ranged' => 1,
		'imobilize' => 0,
		'poison' => 0
	],
	13 => [
		'id' => 13,
		'name' => 'tree branch',
		'chance' => 7,
		'ranged' => 0,
		'imobilize' => 0,
		'poison' => 0
	],
	14 => [
		'id' => 14,
		'name' => 'throwing knife',
		'chance' => 20,
		'ranged' => 1,
		'imobilize' => 0,
		'poison' => 0
	],
	15 => [
		'id' => 15,
		'name' => 'poisoned dart',
		'chance' => 10,
		'ranged' => 1,
		'imobilize' => 0,
		'poison' => 1
	],
	16 => [
		'id' => 16,
		'name' => 'dragon dildo',
		'chance' => 37,
		'ranged' => 0,
		'imobilize' => 0,
		'poison' => 0
	]
];

# The key for each sub array is a weapon id. One value will be chosen to be displayed 
$weapon_fatalities = [
	0 => [
		'brutally killed',
		'stabbed',
		'sliced',
		'cuts',
		'beheaded'
	],
	1 => [
		'crushed the head of',
		'beat to death',
		'impales'
	],
	2 => [
		'stabbed',
		'sliced',
		'cuts'
	],
	3 => [
		'strangled'
	],
	4 => [
		'shoots'
	],
	5 => [
		'shoots'
	],
	6 => [
		'cuts',
		'beheaded'
	],
	7 => [
		'pulls the eyes out of',
		'stabs throat of'
	],
	8 => [
		
	],
	9 => [
		
	],
	10 => [
		
	],
	11 => [
		
	],
	12 => [
		
	],
	13 => [
		
	],
	14 => [
		'headshot\'s',
		'neckshot\'s'
	]
];

$items = [
	0 => [
		'id' => 0,
		'name' => 'med kit',
		'type' => 'antidote'
	],
	1 => [
		'id' => 1,
		'name' => 'pocket knife',
		'type' => 'blade'
	],
];

$participants = [];
$busy_targets = [];
# A message like the one above
$turn_results = '```css' . "\n\n";

# ...
$query = 'SELECT * FROM hg_participants';
$result = $database->MYSQL->query($query);
# Fills the $participants array with the data of every participant from the database
while ($row = $result->fetch_assoc()) {
	$participants[] = $row;
}

// shuffle_assoc($participants);

$count = 0;

foreach ($participants as $player) {
	if ($player['dead'] == 0) {
		$count++;
		$winner = $player;
	}
}

if ($count == 1) {
	$this->say('```css' . "\n" 
		. 'The winner is [' . $winner['name'] . ']!' . "\n"
		. 'From district #' . $winner['district'] . "\n"
		. $winner['kills'] . ' kills' . "\n"
		. '```'
		);

	return;
}

# For each element of the $participant array, reffered to as $player
foreach ($participants as &$player) {
	# Stores in variables everything about the participant
	$player_id = $player['id'];
	$player_name = $player['name'];
	# Ternary opertor, if no weapon set it to null, otherwise set it to the player's weapon
	$player_weapon = $player['weapon'] !== null ? $weapons[$player['weapon']] : $player['weapon'];
	$player_item = $player['item'];
	# Explodes the $player['friends'] by ',' comma, which means it's splitting the string at each comma and turn the data between them into an array element
	$player_friends = explode(',', $player['friends']);
	$player_dead = $player['dead'];
	$player_imobilized = $player['imobilized'];
	$player_poisoned = $player['poisoned'];
	$player_kills = $player['kills'];

	# If the player is dead OR busy (busy means it has been targeted by someone else already), skip one cycle of the loop
	if ($player_dead == 1 || in_array($player_id, $busy_targets) || $player_imobilized == 1) {
		continue;
	}

	$potential_targets = [];

	$query = 'SELECT * FROM hg_participants WHERE dead = 0 AND NOT id = "' . $player_id . '"';
	$result = $database->MYSQL->query($query);
	while ($row = $result->fetch_assoc()) {
		$potential_targets[] = $row;
	}

	# A random number between 1 and 100
	$target_chance = rand(1, 100);
	$target = false;

	# If the generated number $target_chance is greater than 0 (for testing) AND the $potential_targets array has at least one element
	if ($target_chance >= 75 && !empty($potential_targets)) {
		# Picks a target randomly
		$target = $potential_targets[rand(0, count($potential_targets) - 1)];
	}

	if ($target) {
		$target_id = $target['id'];
		$target_name = $target['name'];
		$target_weapon = $target['weapon'] !== null ? $weapons[$target['weapon']] : $target['weapon'];
		$target_item = explode(',', trim($target['item']));
		$target_friends = explode(',', $target['friends']);
		$target_dead = $target['dead'];
		$target_imobilized = $target['imobilized'];
		$target_poisoned = $target['poisoned'];

		$busy_targets[] = $target_id;
	}

	if ($player_item == null) {
		$item_chance = rand(1, 100);

		if ($item_chance >= 30 && $item_chance <= 37) {
			$random_item = rand(0, count($items) - 1);

			$player_item = $items[$random_item];

			$turn_results .= '[' . $player_name . '] found a ' . $player_item['name'] . "\n";

			$query = 'UPDATE hg_participants SET item = ' . $player_item['id'] . ' WHERE id = "' . $player_id . '"';
			$result = $database->MYSQL->query($query);
		}
	}

	if ($player_poisoned == 1) {
		if ($player_item !== null && $items[$player_item]['type'] == 'antidote') {
			$item_id = $items[$player_item]['id'];
			$item_name = $items[$player_item]['name'];

			$query = 'UPDATE hg_participants SET item = NULL WHERE id = "' . $player_id . '"';
			$result = $database->MYSQL->query($query);

			$query = 'UPDATE hg_participants SET poisoned = 0 WHERE id = "' . $player_id . '"';
			$result = $database->MYSQL->query($query);

			$turn_results .= '[' . $player_name . '] cured his poisoning with a ' . $item_name . "\n";
		} else {
			$player_dead = true;
			$query = 'UPDATE hg_participants SET dead = 1 WHERE id = "' . $player_id . '"';
			$result = $database->MYSQL->query($query);

			$turn_results .= '[' . $player_name . '] died from poisoning' . "\n";

			continue;
		}
	}

	# If the player doesn't have a weapon
	if ($player_weapon == null) {
		# A generated number between 1 and 100
		$weapon_chance = rand(1, 100);

		# Its chance to find a weapon. It must be greater than 70
		if ($weapon_chance >= 75) {
			# Choose a random element from the $weapons array
			$random_weapon = rand(0, count($weapons) - 1);

			# Stores the weapon data for the player
			$player_weapon = $weapons[$random_weapon];

			$turn_results .= '[' . $player_name . '] found a ' . $player_weapon['name'] . "\n";

			# Insert it into the database (to check and ignore this if statement next time)
			$query = 'UPDATE hg_participants SET weapon = "' . $random_weapon . '" WHERE id = "' . $player_id . '"';
			$result = $database->MYSQL->query($query);
		}
	} else {
		# Chance to found another weapon and switch the current one
		$weapon_switch_chance = rand(1, 100);

		if ($weapon_switch_chance >= 80) {
			$random_weapon = rand(0, count($weapons) - 1);
			$last_weapon = $player_weapon;
			$new_weapon = $weapons[$random_weapon];

			if ($new_weapon['id'] !== $last_weapon['id'] && $new_weapon['chance'] >= $last_weapon['chance']) {
				$player_weapon = $new_weapon;

				$turn_results .= '[' . $player_name . '] decided to switch his ' . $last_weapon['name'] . ' with a ' . $new_weapon['name'] . "\n";

				# Insert it into the database (to check and ignore this if statement next time)
				$query = 'UPDATE hg_participants SET weapon = "' . $random_weapon . '" WHERE id = "' . $player_id . '"';
				$result = $database->MYSQL->query($query);
			}
		}

		# Message for when the player dies
		@$player_killed_target = '[' . $player_name . '] ' . $weapon_fatalities[$player_weapon['id']][rand(0, count($weapon_fatalities[$player_weapon['id']]) - 1)] . ' [' . $target_name . ']' . ' with a ' . $player_weapon['name'] . "\n";
		# Message for when the target dies
		@$target_killed_player = '[' . $target_name . '] has been attacked by [' . $player_name . '] but he ' . $weapon_fatalities[$target_weapon['id']][rand(0, count($weapon_fatalities[$target_weapon['id']]) - 1)] . ' [' . $player_name . '] with a ' . $target_weapon['name'] . "\n";
		@$target_escaped_player = '[' . $target_name . ']' . ' escaped [' . $player_name . '] attack' . "\n";
		@$player_miss_target = '[' . $player_name . '] missed [' . $target_name . '] with a ' . $player_weapon['name'] . "\n";
		@$player_not_fatal = '[' . $player_name . '] attacked [' . $target_name . '] with a ' . $player_weapon['name'] . ' but it was not fatal' . "\n";
		@$player_dildoed_target = '[' . $target_name . '] was imobilized and [' . $player_name . '] had a ' . $player_weapon['name'] . '... Let\'s not talk about this' . "\n";
		@$player_imobilized_target = '[' . $player_name . '] imobilized [' . $target_name . '] with his ' . $player_weapon['name'] . "\n";
		@$player_poisoned_target = '[' . $player_name . '] poisoned [' . $target_name . '] with his ' . $player_weapon['name'] . "\n";
		@$player_killed_imobilized_target = '[' . $target_name . '] was imobilized and [' . $player_name . '] killed him with his ' . $player_weapon['name'] . "\n";

		if ($target) {
			# If the weapon has the 'ranged' property set to 1
			if ($player_weapon['ranged'] == 1) {
				# If a random number between 1 and 100 is greater than the weapon's 'chance' property (which means the weapon's chance to kill the target)
				$ranged_chance = rand(1, 100);
				if ($player_weapon['chance'] >= $ranged_chance) {
					# Set the target dead
					$target_dead == true;
				} else {
					$turn_results .= $player_miss_target;
				}
			# If the weapon is not ranged, the target gets the chance to fight back
			} else {
				if ($target_imobilized == 0) {
					$imobilize_chance = rand(1, 100);
					$poison_chance = rand(1, 100);

					# If the target doesn't have a weapon it can either die or escape (i will add the punching later)
					if ($target_weapon == null) {
						# Random 1 - 100
						$escape_chance = rand(1, 100);

						# If the target's escape chance is greater than 90%, it will escape the attack
						if ($escape_chance >= 90) {
							$target_escaped = true;
							$turn_results .= $target_escaped_player;
						# If it didn't escape, set the target death to true and concatenate the "player killed target with weapon" to the turn message
						} else {
							if ($player_weapon['imobilize'] == 1 && $imobilize_chance >= 10) {
								$target_imobilized = 1;
								$turn_results .= $player_imobilized_target;
							} elseif ($player_weapon['poison'] == 1 && $poison_chance >= 10) {
								$target_poisoned = 1;
								$turn_results .= $player_poisoned_target;
							} else {
								$target_dead = true;
								$turn_results .= $player_killed_target;
							}
						}
					# If the target has a weapon, it can fight back
					} else {
						# Random 1 - 100
						$kill_chance = rand(1, 100);

						# The following can have 3 possibilities
						# 1. Player kills target
						# 2. Target kills player
						# 3. Nothing happens

						# If player's weapon chance of killing is greater than the generated number, set target dead and add to the turn message
						if ($player_weapon['chance'] >= $kill_chance) {
							$target_dead = true;
							$turn_results .= $player_killed_target;
						# If the target's weapon chance of killing is greater than the generated number, do the same but switch the roles (player will die, not the target)
						} elseif ($target_weapon['chance'] >= $kill_chance) {
							$player_dead = true;
							$turn_results .= $target_killed_player;
						} else {
							if ($player_weapon['imobilize'] == 1 && $imobilize_chance >= 10) {
								$target_imobilized = 1;
								$turn_results .= $player_imobilized_target;
							} elseif ($player_weapon['poison'] == 1 && $poison_chance >= 10) {
								$target_poisoned = 1;
								$turn_results .= $player_poisoned_target;
							} else {
								$turn_results .= $player_not_fatal;
							}
						}
					}
				} else {
					if ($player_weapon['id'] == 16) {
						$target_dead = true;
						$turn_results .= $player_dildoed_target;
					} else {
						$target_dead = true;
						$turn_results .= $player_killed_imobilized_target;
					}
				}
			}
		}
	}

	# Stores the player and target current state (dead or alive) into the database for the next turn
	if ($player_dead) {
		$query = 'UPDATE hg_participants SET dead = 1 WHERE id = "' . $player_id . '"';
		$result = $database->MYSQL->query($query);

		$player['dead'] = 1;
	} elseif ($target) {
		if ($target_dead) {
			$query = 'UPDATE hg_participants SET dead = 1 WHERE id = "' . $target_id . '"';
			$result = $database->MYSQL->query($query);

			$query = 'UPDATE hg_participants SET kills = kills + 1 WHERE id = "' . $player_id . '"';
			$result = $database->MYSQL->query($query);
		}
	} elseif (isset($target_imobilized) && $target_imobilized == 1) {
		$query = 'UPDATE hg_participants SET imobilized = 1 WHERE id = "' . $target_id . '"';
		$result = $database->MYSQL->query($query);
	} elseif (isset($target_poisoned) && $target_poisoned == 1) {
		$query = 'UPDATE hg_participants SET poisoned = 1 WHERE id = "' . $target_id . '"';
		$result = $database->MYSQL->query($query);
	}
}

# Closing the turn message
$turn_results .= '```';

# Sending the message to the chat
$this->say($turn_results);
?>