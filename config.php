<?php
define('BOT_USERNAME', 'Banan');
define('MASTER', '');
define('MASTER_ID', '');
define('COMMAND_KEYWORD', '$');

define('DB_HOST', '');
define('DB_USER', '');
define('DB_PASSWORD', '');
define('DB_DATABASE', '');

$allowed_users = [
	'*'
];

$allowed_commands = [
	'harrass',
	'compliment',
	'whosyourdaddy',
	'selfdefense',
	'gj',
	'fuckoff',
	'kick',
	'bitchslap',
	'marco',
	'dead',
	'8ball',
	'psychopass'
];

define('ALLOWED_USERS', $allowed_users);
define('ALLOWED_COMMANDS', $allowed_commands);
?>