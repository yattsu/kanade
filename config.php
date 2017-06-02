<?php
define('BOT_USERNAME', 'Banan');
define('MASTER_USERNAME', 'ambanane');
define('MASTER_ID', '170969285110267904');
define('COMMAND_KEYWORD', '$');

define('DB_HOST', '');
define('DB_USER', '');
define('DB_PASSWORD', '');
define('DB_DATABASE', '');

$allowed_users = [
	'*',
	'170969285110267904'
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
	'settime',
	'afk',
	'afkoff',
	'daily',
	'give',
	'schmeckles'
];

define('ALLOWED_USERS', $allowed_users);
define('ALLOWED_COMMANDS', $allowed_commands);
?>