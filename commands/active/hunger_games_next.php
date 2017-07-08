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

# The key for each sub array is a weapon id. One value will be chosen to be displayed 
$weapon_fatalities = [
	0 => [
		'Brutally killed',
		'Swingy swingy'
	],
	1 => [
		'Crushed the head of',
		'Beat to death'
	],
	2 => [
		'Stabbed',
		'Sliced'
	],
	3 => [
		'Strangled'
	]
];

$participants = [];
# A message like the one above
$turn_results = '```css' . "\n\n";

# ...
$query = 'SELECT * FROM hg_participants';
$result = $database->MYSQL->query($query);
# Fills the $participants array with the data of every participant from the database
while ($row = $result->fetch_assoc()) {
	$participants[] = $row;
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
	$player_busy = $player['busy'];
	$player_kills = $player['kills'];

	# If the player is dead OR busy (busy means it has been targeted by someone else already), skip one cycle of the loop
	if ($player_dead == 1 || $player_busy == 1) {
		continue;
	}

	$potential_targets = [];

	# For each $participants array reffered to as $target
	foreach ($participants as $target) {
		# If the target id is not the same as the player id (making sure i doesn't target itself) AND the player is not busy (it hasn't been targeted this turn) AND it's not dead
		if ($target['id'] !== $player_id && $target['busy'] == 0 && $target['dead'] == 0) {
			# Add it to the $potential_targets array
			$potential_targets[] = $target;
		}
	}

	# A random number between 1 and 100
	$target_chance = rand(1, 100);
	$target = false;
	$target_dead = false;
	$target_poisoned = false;
	$target_starving = false;
	$target_freezing = false;

	# If the generated number $target_chance is greater than 0 (for testing) AND the $potential_targets array has at least one element
	if ($target_chance > 0 && !empty($potential_targets)) {
		# Picks a target randomly
		$target = $potential_targets[rand(0, count($potential_targets) - 1)];
	}

	if ($target !== false) {
		// foreach ($participants as &$participant) {
		// 	if ($participant['id'] == $target['id']) {
		$target_busy = 1;

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

	# If the player doesn't have a weapon
	if ($player_weapon == null) {
		# A generated number between 1 and 100
		$weapon_chance = rand(1, 100);

		# Its chance to find a weapon. It must be greater than 70
		if ($weapon_chance > 70) {
			# Choose a random element from the $weapons array
			$random_weapon = rand(0, count($weapons) - 1);

			# Stores the weapon data for the player
			$player_weapon = $weapons[$random_weapon];

			# Insert it into the database (to check and ignore this if statement next time)
			$query = 'UPDATE hg_participants SET weapon = "' . $random_weapon . '" WHERE id = "' . $player_id . '"';
			$result = $database->MYSQL->query($query);
		}
	} else {
		# Message for when the player dies
		@$player_killed_target = '[' . $player_name . '] ' . $weapon_fatalities[$player_weapon['id']][rand(0, count($weapon_fatalities) - 1)] . ' [' . $target_name . ']' . ' with a ' . $player_weapon['name'] . "\n";
		# Message for when the target dies
		@$target_killed_player = '[' . $target_name . '] ' . $weapon_fatalities[$target_weapon['id']][rand(0, count($weapon_fatalities) - 1)] . ' [' . $player_name . ']' . ' with a ' . $target_weapon['name'] . "\n";

		if ($target !== false) {
			# If the weapon has the 'ranged' property set to 1
			if ($player_weapon['ranged'] == 1) {
				# If a random number between 1 and 100 is greater than the weapon's 'chance' property (which means the weapon's chance to kill the target)
				if (rand(1, 100) < $player_weapon['chance']) {
					# Set the target dead
					$target_dead == true;
				}
			# If the weapon is not ranged, the target gets the chance to fight back
			} else {
				# If the target doesn't have a weapon it can either die or escape (i will add the punching later)
				if ($target_weapon == 0) {
					# Random 1 - 100
					$escape_chance = rand(1, 100);

					# If the target's escape chance is greater than 90%, it will escape the attack
					if ($escape_chance > 90) {
						$target_escaped = true;
					# If it didn't escape, set the target death to true and concatenate the "player killed target with weapon" to the turn message
					} else {
						$target_dead = true;
						$turn_results .= $player_killed_target;
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
						$turn_results[] = $target_killed_player;
					}
				}
			}
		}
	}

	# Stores the player and target current state (dead or alive) into the database for the next turn
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

# Closing the turn message
$turn_results .= '```';

# Sending the message to the chat
$this->say($turn_results);
?>