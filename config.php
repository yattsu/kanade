<?php
define('BOT_USERNAME', 'Banan');
define('MASTER', '');
define('MASTER_ID', '');
define('COMMAND_KEYWORD', '$');

define('DB_HOST', '127.0.0.1');
define('DB_USER', 'root');
define('DB_PASSWORD', '');
define('DB_DATABASE', 'banan');

$allowed_users = [
	'*',
	''
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
	'psychopass',
	'emoji',
	'time',
	'settime'
];

define('ALLOWED_USERS', $allowed_users);
define('ALLOWED_COMMANDS', $allowed_commands);
?>