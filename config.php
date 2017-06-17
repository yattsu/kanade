<?php
define('BOT_USERNAME', 'Kanade');
define('TOKEN', '');
define('MASTER_USERNAME', 'ambanane');
define('MASTER_ID', '170969285110267904');
define('COMMAND_KEYWORD', '$');

define('DB_HOST', '127.0.0.1');
define('DB_USER', 'root');
define('DB_PASSWORD', '');
define('DB_DATABASE', 'kanade');

define('API', [
	'pollution' => ''
]);

$allowed_users = [
	'*',
	'170969285110267904'
];

$allowed_commands = [
	'harass',
	'compliment',
	'whosyourdaddy',
	'selfdefense',
	'gj',
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
	'schmeckles',
	'startbet',
	'bet',
	'betresults',
	'lol',
	'members',
	'emojify',
	'air',
	'memory',
	'shutdown'
];

define('ALLOWED_USERS', $allowed_users);
define('ALLOWED_COMMANDS', $allowed_commands);
?>