<?php
require_once 'config.php';

class Banan
{
	public $MESSAGE;
	public $BOT_USERNAME;
	private $MASTER_USERNAME;
	private $MASTER_ID;
	private $COMMAND_KEYWORD;
	private $ALLOWED_USERS;
	private $ALLOWED_COMMANDS;

	public $command;
	private $command_argument;

	public function __construct($message)
	{
		$this->MESSAGE = $message;
		$this->BOT_USERNAME = BOT_USERNAME;
		$this->MASTER_USERNAME = MASTER_USERNAME;
		$this->MASTER_ID = MASTER_ID;
		$this->COMMAND_KEYWORD = COMMAND_KEYWORD;
		$this->ALLOWED_USERS = ALLOWED_USERS;
		$this->ALLOWED_COMMANDS = ALLOWED_COMMANDS;

		$message_exploded = explode(' ', $message->content);
		$this->command = str_replace($this->COMMAND_KEYWORD, '', $message_exploded[0]);
		$this->command_argument = count($message_exploded) > 1 ? $message_exploded[1] : false;
	}


	public function isBotMessage()
	{
		$author_username = $this->MESSAGE->author->username;
		$bot_username = $this->BOT_USERNAME;

		if ($author_username == $bot_username) {
			return true;
		}

		return false;
	}

	public function isUserAllowed()
	{
		if ($this->ALLOWED_USERS[0] == '*') {
			return true;
		}

		$author_id = $this->MESSAGE->author->id;

		if (!in_array($author_id, $this->ALLOWED_USERS)) {
			return false;
		}

		return true;
	}

	public function isCommandAllowed()
	{
		if (in_array($this->command, $this->ALLOWED_COMMANDS)) {
			return true;
		}

		return false;
	}

	public function isCommand()
	{
		@$command_keyword = trim($this->MESSAGE->content[0]);
		@$command_position = trim($this->MESSAGE->content[1]);

		if ($command_keyword == $this->COMMAND_KEYWORD && !empty($command_position)) {
			return true;
		}

		return false;
	}

	public function say($message)
	{
		$this->MESSAGE->channel->sendMessage(ucfirst($message));
	}

	public function reply($message)
	{
		$this->MESSAGE->reply($message);
	}

	public function harrass()
	{
		$insults = [
			'You\'re a penus.',
			'You’re a butt... face!',
			'I bet you kiss girls, faggot.',
			'Your mom.',
			'You are not the best person i know!',
			'I bet you stink.',
			'The smartest thing that ever came out of your mouth is a penus.',
			'I\'d slap you, but shit stains.',
			'I keel you.',
			'Is a fag.',
			'Keel yourself :gun:',
			'Has no life.'
		];

		if (strpos($this->command_argument, $this->MASTER_ID) !== false) {
			$this->say('I would never harrass my senpai!');

			return;
		}

		$random_insult = rand(0, count($insults) - 1);

		$this->say($this->command_argument . ' ' . $insults[$random_insult]);
	}

	public function randomHarrass()
	{
		$insults = [
			'Ok so this is your harrassment.',
			'*Harrass.*',
			'You\'re weak.',
			'Give up mate.',
			'Find a purpose in life.',
			'You can\'t do shit.',
			'You wanted harrassment? Now you have it.',
			'You heard something? I heard a no one.',
			'-> You :gun:',
			'Git gud scrub.',
			'Ay! Shut up.',
			'No.',
			'Stop. Talking... Please.',
			'Cringe.',
			'Don\'t kill the chat please.',
			'FYI, that was unnecessary.',
			'I know you\'re lonely, but spamming the chat isn\'t the cure.',
			'I don\'t think I can add anything to that.',
			'You\'re doing my job for me right now. Keep it up. :thumbsup:',
			'Stop. Talking... Please. :expressionless:',
			'Ha ha ha. Funny.',
			'That is the stupidest thing I\'ve heard in a while.',
			'Believe in yourself. You are better than this.',
			'That is not something you should say ever again.',
			'People are too nice to tell you to stop.',
			'That\'s not something even I would say.',
			':thinking: ...',
			'Jesus, I\'m glad I\'m not like that.',
			'Can you not? :canunot:',
			'Best damage control now is to stop talking.',
			'No. one. cares.',
			'Do you still have hopes for someone speaking to you?',
			'^ This gave me cancer.',
			'Are you done yet?',
			'Tell me when you\'re done so I can give a fuck again.',
			'I\'m as alive as you are.',
			'You can continue speaking or do us all a favor and shut up.',
			'I really don\'t care.',
			'Why are you even trying?',
			'Oh look. He knows how to write.',
			'Don\'t reply to him or he might think you actually care.',
			'Everybody else, just ignore him.',
			'Don\'t worry, he doesn\'t know what he\'s talking about either.',
			'Now I\'m really confused. But I think you\'re more confused than me.',
			'I was about to say something intelligent to you but... Forget it.',
			'What\'s that? Try to speak properly.',
			':point_left: :poop:'
		];

		$random_harrass = rand(0, count($insults) - 1);

		$victim = [
			'A Wooden Nail'
		];

		$safe_users = [
			'ambanane'
		];

		$safe_channels = [
			'introduction'
		];

		$channel_name = $this->MESSAGE->channel->name;

		if (in_array($channel_name, $safe_channels)) {
			return;
		}

		$chance = rand(1, 100);
		$victim_chance = 3;
		$regular_chance = 1;
		
		$author_username = $this->MESSAGE->author->username;

		if (in_array($author_username, $victim)) {
			if ($chance <= $victim_chance) {
				$this->reply($insults[$random_harrass]);
			}
		}

		if (!in_array($author_username, $safe_users) && !in_array($author_username, $victim) && $author_username !== $this->BOT_USERNAME) {
			if ($chance <= $regular_chance) {
				$this->reply($insults[$random_harrass]);
			}
		}
	}

	public function nini()
	{
		// $nini_list = [
		// 	'Good night!',
		// 	'Sleep well!',
		// 	'Nini.'
		// ];
		// $nini_random = rand(0, count($nini_list) - 1);

		$message_content = $this->MESSAGE->content;

		$message = explode(' ', $message_content);
		$keyword = 'ni';

		$counter = 0;

		foreach($message as $word) {
			$keyword_instances = substr_count(strtolower($word), strtolower($keyword));
			if($keyword_instances == 2) {
				$counter++;
			}
		}

		if ($counter >= 1) {
			require_once 'model/database.php';
			$database = new Database;

			$query = 'SELECT last_nini FROM nini_momo ORDER BY id DESC';
			$result = $database->MYSQL->query($query);
			$row = $result->fetch_assoc();

			$current_time = time();
			$last_nini = $row['last_nini'];

			if (($current_time - $last_nini) < 600) {
				return;
			}

			$query = 'UPDATE nini_momo SET last_nini = ' . $current_time . ' ORDER BY id DESC LIMIT 1';
			$result = $database->MYSQL->query($query);

			$this->say('Nini');

			return true;
		}

		return false;
	}

	public function momo()
	{
		// $momo_list = [
		// 	'Good morning!',
		// 	'Morning sunshine!',
		// 	'Momo.'
		// ];
		// $momo_random = rand(0, count($momo_list) - 1);

		$message = explode(' ', $this->MESSAGE->content);
		$keyword = 'mo';

		$counter = 0;

		foreach($message as $word) {
			$keyword_instances = substr_count(strtolower($word), strtolower($keyword));
			if($keyword_instances == 2) {
				$counter++;
			}
		}

		if ($counter >= 1) {
			require_once 'model/database.php';
			$database = new Database;

			$query = 'SELECT last_momo FROM nini_momo ORDER BY id DESC';
			$result = $database->MYSQL->query($query);
			$row = $result->fetch_assoc();

			$current_time = time();
			$last_momo = $row['last_momo'];

			if (($current_time - $row['last_momo']) < 600) {
				return;
			}

			$query = 'UPDATE nini_momo SET last_momo = ' . $current_time . ' ORDER BY id DESC LIMIT 1';
			$result = $database->MYSQL->query($query);

			$this->say('Momo');

			return true;
		}

		return false;
	}

	public function compliment()
	{
		$compliments = [
			'You\'re a qtpie.',
			'You\'re niceu.',
			'You\'re my best friendo.',
			'I like the way you look today.',
			'I would date you.',
			'I like your face!',
			'You are the nicest person on this server.',
			'What a cutie.',
			'Aren\'t you the most beautiful thing in the world?',
			'I <3 you. (pls no flirting)',
			'Who\'s handsome? You\'re handsome!',
			'I could talk to you all day but the others are getting jealous.'
		];

		$random_compliment = rand(0, count($compliments) - 1);

		$command_argument = $this->command_argument;

		$this->say($command_argument . ' ' . $compliments[$random_compliment]);
	}

	public function whosyourdaddy()
	{
		$author_id = $this->MESSAGE->author->id;
		$master_id = $this->MASTER_ID;

		if ($author_id == $master_id) {
			$this->reply('You :heart:');
		} else {
			$this->say('<@' . $master_id . '> Is my senpai.');
		}
	}

	public function selfdefense()
	{
		$this->reply('I have less than 50 words in my vocabulary and i\'m still smarter than you.');
	}

	public function gj()
	{
		$author_id = $this->MESSAGE->author->id;
		$master_id = $this->MASTER_ID;

		if ($author_id == $master_id) {
			$this->reply(':flushed: T-thank you senpai!');
		} else {
			$this->reply('Lol thanks.');
		}
	}

	public function kick()
	{
		$gifs = [
		'http://gph.is/23X7fZF',
		'http://gph.is/XLWs5q',
		'http://gph.is/1PwuvoE',
		'http://gph.is/1MbFkKS'
		];

		$random_gif = rand(0, count($gifs) - 1);

		$author_username = $this->MESSAGE->author->username;
		$command_argument = $this->command_argument;

		$this->say($author_username . ' kicked ' . $command_argument . "\n" . $gifs[$random_gif]);
	}

	public function bitchslap()
	{
		$gifs = [
		'http://gph.is/16Q8alO',
		'http://gph.is/14innny',
		'http://gph.is/1PlcayB',
		'http://gph.is/10HiqXX'
		];

		$random_gif = rand(0, count($gifs) - 1);

		$author_username = $this->MESSAGE->author->username;
		$command_argument = $this->command_argument;

		$this->say($author_username . ' bitch slapped ' . $command_argument . "\n" . $gifs[$random_gif]);
	}

	public function marco()
	{
		$this->say(':water_polo:');
	}

	public function dead()
	{
		$gifs = [
			'http://gph.is/1LyAEQ2'
		];

		$random_gif = rand(0, count($gifs) - 1);

		$this->say(' ' . $gifs[$random_gif]);
	}

	public function ball8()
	{
		$command_argument = $this->command_argument;
		$message_content = $this->MESSAGE->content;
		$author_username = $this->MESSAGE->author->username;

		if (!$command_argument) {
			$this->say('I\'m not broken so you did something wrong.');

			return;
		} elseif (!strpos($message_content, '?')) {
			$this->say('8ball needs a question, dummy.');

			return;
		}

		$random = rand(1, 2);

		$yes_list = [
			'Yep',
			'Definitely yes',
			'I think yes',
			'I\'m 100% sure',
			'Of course',
			'Sure thing',
			':white_check_mark:'
		];

		$yes_random = rand(0, count($yes_list) - 1);

		$no_list = [
			'Nop',
			'Hell no',
			'This will never happen',
			'How can you even ask that? Of course not',
			'Sorry but no',
			'Negative',
			':no_entry:'
		];

		$no_random = rand(0, count($no_list) - 1);

		if ($random == 1) {
			$this->say(':8ball: | ' . $yes_list[$yes_random] . ', **' . $author_username . '**.');
		} else {
			$this->say(':8ball: | ' . $no_list[$no_random] . ', **' . $author_username . '**.');
		}
	}

	public function storepsychopass()
	{
		require_once 'model/database.php';
		$database = new Database;

		$message_content = $this->MESSAGE->content;
		$author_id = $this->MESSAGE->author->id;
		$author_username = $this->MESSAGE->author->username;
		$author_discriminator = $this->MESSAGE->author->discriminator;

		$bad_list = [
			'fuck' => 1,
			'jerk' => 1,
			'asshole' => 1,
			'hate' => 1,
			'hell' => 1,
			'shit' => 1,
			'dumb' => 1,
			'dick' => 2,
			'ass' => 2,
			'idiot' => 2,
			'stupid' => 2,
			'moron' => 2,
			'boobs' => 2,
			'tits' => 2,
			'pussy' => 2,
			'fag' => 2,
			'bitch' => 3,
			'faggot' => 3,
			'dead' => 3,
			'murder' => 4,
			'suicide' => 4,
			'kill' => 4,
			'gay' => 5,
			'retard' => 5,
			'retarded' => 5
		];

		$good_list = [
			'luck' => 2,
			'right' => 2,
			'nice' => 2,
			'ok' => 2,
			'hi' => 2,
			'hey' => 2,
			'handsome' => 3,
			'beautiful' => 3,
			'apologize' => 7,
			'sorry' => 7
		];

		$amount = 0;

		$message_exploded = explode(' ', $message_content);

		foreach ($message_exploded as $word) {
			$word = strtolower($word);
			
			if (array_key_exists($word, $bad_list)) {
				$amount += $bad_list[$word];
			} elseif (array_key_exists($word, $good_list)) {
				$amount -= $good_list[$word];
			}
		}

		$query = 'SELECT * from users WHERE user_id = ' . $author_id;
		$result = $database->MYSQL->query($query);
		$rows_number = $result->num_rows;

		if ($rows_number == 0) {
			$query = 'INSERT INTO users (username, user_id, discriminator, psychopass) VALUES ("' . $author_username . '", "' . $author_id . '", "' . $author_discriminator . '", "0")';
		} else {
			$row = $result->fetch_assoc();
			$psychopass = $row['psychopass'];

			if (($psychopass + $amount) < 0) {
				$query = 'UPDATE users SET psychopass = "0" WHERE user_id = "' . $author_id . '"';
			} else {
				$query = 'UPDATE users SET psychopass = psychopass + "' . $amount . '" WHERE user_id = "' . $author_id . '"';
			}
		}

		$result = $database->MYSQL->query($query);
	}

	public function psychopass()
	{
		$command_argument = $this->command_argument;

		$sibyl_error = '`[SIBYL SYSTEM]: ERROR - The psychopass cannot be determined.`';

		if (!$command_argument) {
			return;
		}

		if ($command_argument == '@everyone' || $command_argument == '@here') {
			$this->say($sibyl_error);

			return;
		}

		require_once 'model/database.php';
		$database = new Database;

		$target_user_id = str_replace(array('<@', '>', '!'), '', $command_argument);

		$query = 'SELECT * FROM users WHERE user_id = ' . $target_user_id;
		$result = $database->MYSQL->query($query);
		$rows_number = $result->num_rows;

		if ($rows_number == 0) {
			$this->say($sibyl_error);

			return;
		}

		$row = $result->fetch_assoc();
		$psychopass = $row['psychopass'];
		$verdict = '';
		
		if ($psychopass < 100) {
			$verdict = '**[**<@' . $target_user_id . '>**] Crime Coefficient is ' . $psychopass . '% -** `Suspect is not a target for enforcement action. The trigger of Dominator will be locked.`';
		} elseif ($psychopass < 300) {
			$verdict = '**[**<@' . $target_user_id . '>**] Crime Coefficient is ' . $psychopass . '% -** `Suspect is classified as a latent criminal and is a target for enforcement action. Dominator is set to Non-Lethal Paralyzer mode. Suspect can then be knocked out using the Dominator.`';
		} else {
			$verdict = '**[**<@' . $target_user_id . '>**] Crime Coefficient is ' . $psychopass . '% -** `Suspect poses a serious threat to the society. Lethal force is authorized. Dominator will automatically switch to Lethal Eliminator.`';
		}

		$this->say($verdict);
	}

	public function storeemoji()
	{
		require_once 'model/database.php';
		$database = new Database;

		$message_content = $this->MESSAGE->content;
		$message_exploded = explode(' ', $message_content);
		$limit = 0;

		foreach($message_exploded as $word) {
			if (strpos($word, '<:') !== false && substr_count($word, ':') == 2 && substr_count($word, '<') == 1 && substr_count($word, '>') == 1) {
				$limit++;

				if ($limit > 2) {
					return;
				}

				$word_exploded = explode(':', $word);
				$emoji_name = $word_exploded[1];
				$emoji_id = str_replace('>', '', $word_exploded[2]);

				$query = 'SELECT * FROM emoji WHERE emoji_id = ' . $emoji_id . ' AND name = "' . $emoji_name . '"';
				$result = $database->MYSQL->query($query);
				$rows_number = $result->num_rows;

				if ($rows_number > 0) {
					$query = 'UPDATE emoji SET times_used = times_used + 1 WHERE emoji_id = ' . $emoji_id;
				} else {
					$query = 'INSERT INTO emoji (name, emoji_id, times_used) VALUES ("' . $emoji_name . '", "' . $emoji_id . '", "' . 1 . '")';
				}

				$result = $database->MYSQL->query($query);
			}
		}
	}

	public function emoji_leaderboard()
	{
		require_once 'model/database.php';
		$database = new Database;

		$query = 'SELECT * FROM emoji ORDER BY times_used DESC LIMIT 5';
		$result = $database->MYSQL->query($query);

		$leaderboard = [];

		while ($row = $result->fetch_assoc()) {
			$leaderboard[] = [
				'name' => $row['name'],
				'id' => $row['emoji_id'],
				'times_used' => $row['times_used']
			];
		}

		$leaderboard_message = "__**`Emoji Leaderboard`**__ \n" . '`';
		$index = 0;

		foreach ($leaderboard as $emoji) {
			$index++;
			$leaderboard_message .= $index . '. `<:' . $emoji['name'] . ':' . $emoji['id'] . '>` - ' . $emoji['times_used'] . ' times' . "\n";
		}

		$leaderboard_message .= '`';

		$this->say($leaderboard_message);
	}

	public function storemessages()
	{
		$message_content = $this->MESSAGE->content;

		if (!$message_content) {
			return;
		}

		require_once 'model/database.php';
		$database = new Database;

		$author_username = $this->MESSAGE->author->username;
		$author_id = $this->MESSAGE->author->id;
		$message_id = $this->MESSAGE->id;
		$time = date('h:i:s', time());
		$date = date('d/m/Y');

		$query = 'INSERT INTO messages (content, author_name, author_id, message_id, send_time, send_date) VALUES ("' . $message_content . '", "' . $author_username . '", "' . $author_id . '", "' . $message_id . '", "' . $time . '", "' . $date . '")';
		$result = $database->MYSQL->query($query);
	}

	public function set_time()
	{
		$command_argument = $this->command_argument;

		if (!$command_argument) {
			return;
		}

		if (!(substr_count($command_argument, '+') || substr_count($command_argument, '-')) || strlen($command_argument) > 3 || !is_numeric(substr($command_argument, 1))) {
			$this->say('`Invalid time`');

			return;
		} elseif (substr($command_argument, 1) > 14) {
			$this->say('`You can\'t set a difference bigger than 14`');

			return;
		}

		require_once 'model/database.php';
		$database = new Database;

		$user_id = $this->MESSAGE->author->id;

		$query = 'UPDATE users SET utc = "' . $command_argument . '" WHERE user_id = ' . str_replace('!', '', $user_id);
		$result = $database->MYSQL->query($query);
	}

	public function time()
	{
		require_once 'model/database.php';
		$database = new Database;

		date_default_timezone_set('UTC');
		$command_argument = $this->command_argument;
		$author_id = str_replace('!', '', $this->MESSAGE->author->id);

		if (!$command_argument) {
			$query = 'SELECT utc FROM users WHERE user_id = "' . $author_id . '"';
		} else {
			if (substr_count($command_argument, '<@') == 1 && substr_count($command_argument, '>') == 1) {
				$start = strpos($command_argument, '<@');
				$end = strpos($command_argument, '>');
				$user_id = str_replace('!', '', substr($command_argument, $start + 2, $end - 2));

				$query = 'SELECT utc FROM users WHERE user_id = "' . $user_id . '"';
			}
		}

		$result = $database->MYSQL->query($query);
		$row = $result->fetch_assoc();

		$utc = $row['utc'];

		if (substr_count($utc, '+') > 0) {
			$time = date('H:i', time() + (3600 * substr($utc, 1)));
		} elseif (substr_count($utc, '-') > 0) {
			$time = date('H:i', time() - (3600 * substr($utc, 1)));
		} else{
			$time = null;
		}

		if (!$time) {
			$this->say('`Set the time with $settime +/- *your UTC offset* (Example: $settime +3)`');
		} else {
			$this->say(':clock11: **|** `' . $time . ' (UTC ' . $utc . ')`');
		}
	}

	public function afk()
	{
		require_once 'model/database.php';
		$database = new Database;

		$author_id = str_replace('!', '', $this->MESSAGE->author->id);
		$author_name = $this->MESSAGE->author->username;

		$query = 'UPDATE users SET afk = "yes" WHERE user_id = "' . $author_id . '"';
		$result = $database->MYSQL->query($query);

		$this->say('**`[AFK mode]: ON, ' . $author_name . '`**');
	}

	public function afkoff()
	{
		require_once 'model/database.php';
		$database = new Database;

		$author_id = str_replace('!', '', $this->MESSAGE->author->id);
		$author_name = $this->MESSAGE->author->username;

		$query = 'UPDATE users SET afk = "no" WHERE user_id = "' . $author_id . '"';
		$result = $database->MYSQL->query($query);

		$this->say('**`[AFK mode]: OFF, ' . $author_name . '`**');
	}

	public function checkafk()
	{
		require_once 'model/database.php';
		$database = new Database;

		$message_content = $this->MESSAGE->content;

		if (!substr_count($message_content, '<@') || !substr_count($message_content, '>')) {
			return;
		}

		$mentions = explode(' ', $message_content);
		$verdict = '';

		foreach ($mentions as $mention) {
			if (!substr_count($mention, '<@') || !substr_count($mention, '>')) {
				continue;
			}

			$start = strpos($mention, '<@');
			$end = strpos($mention, '>');
			$user_id = str_replace('!', '', substr($mention, $start + 2, $end - 2));

			$query = 'SELECT afk FROM users WHERE user_id = "' . $user_id . '"';
			$result = $database->MYSQL->query($query);
			$row = $result->fetch_assoc();

			if ($row['afk'] == 'yes') {
				$query = 'SELECT username FROM users WHERE user_id = "' . $user_id . '"';
				$result = $database->MYSQL->query($query);
				$row = $result->fetch_assoc();

				$verdict .= "**`" . $row['username'] . " is AFK`**\n";
			}
		}

		$this->say($verdict);
	}

	public function daily()
	{
		require_once 'model/database.php';
		$database = new Database;

		$daily_amount = 125;
		$command_argument = $this->command_argument;
		$author_id = $this->MESSAGE->author->id;
		$time_now = time();

		$query = 'SELECT last_daily FROM schmeckles WHERE user_id = "' . $author_id . '"';
		$result = $database->MYSQL->query($query);
		$row = $result->fetch_assoc();

		$last_daily = $row['last_daily'];
		$wait = 24 * 3600;

		if (($time_now - $last_daily) < $wait) {
			$this->reply('You need to wait at least 24h before using this command again');

			return;
		}

		if ((substr_count($command_argument, '<@') || substr_count($command_argument, '<!')) && substr_count($command_argument, '>')) {
			$start = strpos($command_argument, '<@');
			$end = strpos($command_argument, '>');
			$mention_id = str_replace('!', '', substr($command_argument, $start + 2, $end - 2));

			$query = 'SELECT user_id FROM schmeckles WHERE user_id = "' . $mention_id . '"';
			$result = $database->MYSQL->query($query);
			$rows_number = $result->num_rows;

			if ($rows_number < 1) {
				$query = 'INSERT INTO schmeckles (user_id, amount) VALUES ("' . $mention_id . '", "'. $daily_amount .'")';
			} else {
				$query = 'UPDATE schmeckles SET amount = amount + "' . $daily_amount . '" WHERE user_id = "' . $mention_id . '"';
			}

			if (!$result = $database->MYSQL->query($query)) {
				$this->reply('Something went wrong');

				return;
			}

			$query = 'UPDATE schmeckles SET last_daily = "' . $time_now . '" WHERE user_id = "' . $author_id . '"';
			$result = $database->MYSQL->query($query);

			$query = 'SELECT user_id FROM schmeckles WHERE user_id = "' . $author_id . '"';
			$result = $database->MYSQL->query($query);
			$rows_number = $result->num_rows;

			if ($rows_number < 1) {
				$query = 'INSERT INTO schmeckles (user_id, last_daily) VALUES ("' . $author_id . '", "' . time() . '")';
				$result = $database->MYSQL->query($query);
			}

			$this->reply('You gave your daily schmeckles to <@' . $mention_id . '>');

			return;
		}

		$query = 'SELECT user_id FROM schmeckles WHERE user_id = "' . $author_id . '"';
		$result = $database->MYSQL->query($query);
		$rows_number = $result->num_rows;

		if ($rows_number < 1) {
			$query = 'INSERT INTO schmeckles (user_id, amount, last_daily) VALUES ("' . $author_id . '", "' . $daily_amount . '", "' . $time_now . '")';
		} else {
			$query = 'UPDATE schmeckles SET amount = amount + "' . $daily_amount . '", last_daily = "' . $time_now . '" WHERE user_id = "' . $author_id . '"';
		}

		if (!$result = $database->MYSQL->query($query)) {
			$this->reply('Something went wrong');

			return;
		}

		$this->reply('You claimed your **' . $daily_amount . '** schmeckles');
	}

	public function give()
	{
		require_once 'model/database.php';
		$database = new Database;

		$command_argument = $this->command_argument;
		$give_amount = explode(' ', $this->MESSAGE->content);
		$give_amount = isset($give_amount[2]) ? trim($give_amount[2]) : false;
		$author_id = $this->MESSAGE->author->id;

		if (!$command_argument) {
			return;
		}

		if (!$give_amount) {
			return;
		}

		if (!is_numeric($give_amount)) {
			$this->reply('Only `[0-9]` characters are allowed');

			return;
		}

		if ((substr_count($command_argument, '<@') || substr_count($command_argument, '<!')) && substr_count($command_argument, '>')) {
			$start = strpos($command_argument, '<@');
			$end = strpos($command_argument, '>');
			$mention_id = str_replace('!', '', substr($command_argument, $start + 2, $end - 2));

			if ($author_id == $mention_id) {
				$this->reply('You can\'t give yourself schmeckles. That\'s both wrong and sad');

				return;
			}

			$query = 'SELECT amount FROM schmeckles WHERE user_id = "' . $author_id . '"';

			$result = $database->MYSQL->query($query);
			$rows_number = $result->num_rows;

			if ($rows_number < 1) {
				$this->reply('You don\'t have a schmeckles account yet. Create it by typing `$daily`');

				return;
			}

			$row = $result->fetch_assoc();
			$amount = $row['amount'];

			if ($amount < $give_amount) {
				$this->reply('You don\'t have enough schmeckles');

				return;
			}

			$query = 'UPDATE schmeckles SET amount = amount - "' . $give_amount . '" WHERE user_id = "' . $author_id . '"';
			$result = $database->MYSQL->query($query);

			$query = 'SELECT user_id FROM schmeckles WHERE user_id = "' . $mention_id . '"';
			$result = $database->MYSQL->query($query);
			$rows_number = $result->num_rows;

			if ($rows_number < 1) {
				$query = 'INSERT INTO schmeckles (user_id, amount) VALUES ("' . $mention_id . '", "' . $give_amount . '")';
			} else {
				$query = 'UPDATE schmeckles SET amount = amount + "' . $give_amount . '" WHERE user_id = "' . $mention_id . '"';
			}

			if (!$result = $database->MYSQL->query($query)) {
				$this->reply('Something went wrong');

				return;
			}

			$this->reply('You gave <@' . $mention_id . '> **' . $give_amount . '** schmeckles');
		}
	}

	public function schmeckles()
	{
		require_once 'model/database.php';
		$database = new Database;

		$command_argument = $this->command_argument;
		$author_id = $this->MESSAGE->author->id;

		if ((substr_count($command_argument, '<@') || substr_count($command_argument, '<!')) && substr_count($command_argument, '>')) {
			$start = strpos($command_argument, '<@');
			$end = strpos($command_argument, '>');
			$mention_id = str_replace('!', '', substr($command_argument, $start + 2, $end - 2));

			$query = 'SELECT amount FROM schmeckles WHERE user_id = "' . $mention_id . '"';
			$result = $database->MYSQL->query($query);
			$rows_number = $result->num_rows;
			$row = $result->fetch_assoc();
			$amount = $row['amount'];

			$query = 'SELECT username FROM users WHERE user_id = "' . $mention_id . '"';
			$result = $database->MYSQL->query($query);
			$row = $result->fetch_assoc();
			$mention_username = $row['username'];

			if ($rows_number < 1) {
				$this->reply('**' . $mention_username . '** doesn\'t have a schmeckles account');
			} else {
				$this->reply('**' . $mention_username . '** has **' . $amount . '** schmeckles');
			}

			return;
		}

		$query = 'SELECT amount FROM schmeckles WHERE user_id = "' . $author_id . '"';
		$result = $database->MYSQL->query($query);
		$rows_number = $result->num_rows;
		$row = $result->fetch_assoc();
		$amount = $row['amount'];

		if ($rows_number < 1) {
			$this->reply('You don\'t have a schmeckles account yet. Create it by typing `$daily`');
		} else {
			$this->reply('You have **' . $amount . '** schmeckles');
		}
	}
}
?>