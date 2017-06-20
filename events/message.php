<?php

require_once 'model/kanade.php';
$kanade = new kanade($message);

if (!$kanade->isBotMessage()) {
	// if (!$kanade->nini()) {
	// 	$kanade->momo();
	// }

	$kanade->execute_passive([
		'storepsychopass',
		'storeemoji',
		'storemessages',
		'checkafk'
		]);

	if (!$kanade->isCommand()) {
		return;
	}

	if (!$kanade->isUserAllowed()) {
		$kanade->say('`i only take orders from my masters`');

		return;
	}

	if (!$kanade->isCommandAllowed()) {
		// $kanade->say('`command doesn\'t exist`');

		return;
	}

	$command = $kanade->command;

	$aliases = [
		'8ball' => 'ball8',
		'emoji' => 'emoji_leaderboard',
		'settime' => 'set_time'
	];

	if (array_key_exists(strtolower($command), $aliases)) {
		$command = $aliases[$command];
	}

	$kanade->execute_active($command);
}
?>