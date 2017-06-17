<?php
$command_argument = $this->command_argument;

if (!$command_argument) {
	$target_id = $this->MESSAGE->author->id;
} else {
	if (substr_count($command_argument, '<@') < 1 || substr_count($command_argument, '>') < 1) {
	$this->reply('Invalid user');

	return;
	}

	$start = strpos($command_argument, '<@');
	$end = strpos($command_argument, '>');
	$target_id = str_replace('!', '', substr($command_argument, $start + 2, $end - 2));
}

$member = $this->MESSAGE->channel->guild->members[$target_id];

if ($member == null) {
	$this->reply('Can\'t get permissions for user');

	return;
}

$roles = $member['roles'];
$roles_count = count($roles);

$roles_list = [];

foreach ($roles as $role) {
	foreach ($role['permissions'] as $key => $value) {
		$roles_list[$key] = $value;
	}
}


var_dump($roles_list);
?>