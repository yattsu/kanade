<?php
require_once 'config.php';

class Banan
{
	public $MESSAGE;
	public $BOT_USERNAME;
	private $MASTER;
	private $MASTER_ID;
	private $COMMAND_KEYWORD;
	private $ALLOWED_USERS;
	private $ALLOWED_COMMANDS;

	private $message_content;
	public $message_author;
	public $command;
	private $command_argument;

	public function __construct($message)
	{
		$this->MESSAGE = $message;
		$this->BOT_USERNAME = BOT_USERNAME;
		$this->MASTER = MASTER;
		$this->MASTER_ID = MASTER_ID;
		$this->COMMAND_KEYWORD = COMMAND_KEYWORD;
		$this->ALLOWED_USERS = ALLOWED_USERS;
		$this->ALLOWED_COMMANDS = ALLOWED_COMMANDS;

		$this->message_content = $message->content;
		$this->message_author = $message->author->username;
		$message_exploded = explode(' ', $message->content);
		$this->command = str_replace($this->COMMAND_KEYWORD, '', $message_exploded[0]);
		$this->command_argument = count($message_exploded) > 1 ? $message_exploded[1] : false;
	}


	public function isBotMessage()
	{
		if ($this->message_author == $this->BOT_USERNAME) {
			return true;
		}

		return false;
	}

	public function isUserAllowed()
	{
		if ($this->ALLOWED_USERS[0] == '*') {
			return true;
		}

		if (!in_array($this->message_author, $this->ALLOWED_USERS)) {
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
		if (trim($this->message_content[0]) == $this->COMMAND_KEYWORD && !empty(trim($this->message_content[1]))) {
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

	public function message_history()
	{
		$message_list = $this->MESSAGE->channel->getMessageHistory(['limit' => 5,]);

		return $message_list[0]->content;
	}

	public function harrass()
	{
		$random_insults = [
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
		$random = rand(0, count($random_insults) - 1);


		$this->say($this->command_argument . ' ' . $random_insults[$random]);
	}

	public function randomHarrass()
	{
		$harrassments = [
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
		$random_harrass = rand(0, count($harrassments) - 1);

		$victim = [
			'A Wooden Nail',
			'XM Gambit'
		];
		$safe_users = [
			'ambanane',
			'Merwede'
		];
		$safe_channels = [
			'introduction'
		];

		if (in_array($this->MESSAGE->channel->name, $safe_channels)) {
			return;
		}

		$chance = rand(1, 100);
		$victim_chance = 3;
		$regular_chance = 1;
		
		if (in_array($this->message_author, $victim)) {
			echo '>>>[CHANCE]: ' . $victim_chance . '%/' . $chance . "%\n\n";
			if ($chance <= $victim_chance) {
				$this->reply($harrassments[$random_harrass]);
			}
		}

		if (!in_array($this->message_author, $safe_users) && !in_array($this->message_author, $victim) && $this->message_author !== $this->BOT_USERNAME) {
			echo '>>>[CHANCE]: ' . $regular_chance . '%/' . $chance . "%\n\n";
			if ($chance <= $regular_chance) {
				$this->reply($harrassments[$random_harrass]);
			}
		}
	}

	# nini() and momo() are still in development
	public function nini()
	{
		$nini_list = [
			'Good night!',
			'Sleep well!',
			'Nini.'
		];
		$nini_random = rand(0, count($nini_list) - 1);

		$message = explode(' ', $this->message_content);
		$keyword = 'ni';

		$counter = 0;
		foreach($message as $word) {
			$keyword_instances = substr_count(strtolower($word), strtolower($keyword));
			if($keyword_instances == 2) {
				$counter++;
			}
		}

		if ($counter >= 1) {
			// $this->reply($nini_list[$nini_random]);
			echo 'nini';

			return true;
		}

		return false;
	}

	public function momo()
	{
		$momo_list = [
			'Good morning!',
			'Morning sunshine!',
			'Momo.'
		];
		$momo_random = rand(0, count($momo_list) - 1);

		$message = explode(' ', $this->message_content);
		$keyword = 'mo';

		$counter = 0;
		foreach($message as $word) {
			$keyword_instances = substr_count(strtolower($word), strtolower($keyword));
			if($keyword_instances == 2) {
				$counter++;
			}
		}

		if ($counter >= 1) {
			// $this->reply($momo_list[$momo_random]);
			echo 'momo';

			return true;
		}

		return false;
	}

	public function compliment()
	{
		$random_compliments = [
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

		$random = rand(0, count($random_compliments) - 1);

		$this->say($this->command_argument . ' ' . $random_compliments[$random]);
	}

	public function whosyourdaddy()
	{
		if ($this->MESSAGE->author->id == $this->MASTER_ID) {
			$this->reply('You :heart:');
		} else {
			$this->say('<@' . $this->MASTER_ID . '> Is my senpai.');
		}
	}

	public function selfdefense()
	{
		$this->reply('I have less than 50 words in my vocabulary and i\'m still smarter than you.');
	}

	public function gj()
	{
		if ($this->MESSAGE->author->id == $this->MASTER_ID) {
			$this->reply(':flushed: T-thank you senpai!');
		} else {
			$this->reply('Lol thanks.');
		}
	}

	public function kick()
	{
		$gif_urls = [
		'http://gph.is/23X7fZF',
		'http://gph.is/XLWs5q',
		'http://gph.is/1PwuvoE',
		'http://gph.is/1MbFkKS'
		];
		$random = rand(0, count($gif_urls) - 1);

		$this->say($this->message_author . ' kicked ' . $this->command_argument . "\n" . $gif_urls[$random]);
	}

	public function bitchslap()
	{
		$gif_urls = [
		'http://gph.is/16Q8alO',
		'http://gph.is/14innny',
		'http://gph.is/1PlcayB',
		'http://gph.is/10HiqXX'
		];
		$random = rand(0, count($gif_urls) - 1);

		$this->say($this->message_author . ' bitch slapped ' . $this->command_argument . "\n" . $gif_urls[$random]);
	}

	public function marco()
	{
		$this->say(':water_polo:');
	}

	public function dead()
	{
		$gif_urls = [
			'http://gph.is/1LyAEQ2'
		];
		$random = rand(0, count($gif_urls) - 1);

		$this->say(' ' . $gif_urls[$random]);
	}

	public function ball8()
	{
		if (!$this->command_argument) {
			$this->say('I\'m not broken so you did something wrong.');
			return;
		} elseif (!strpos($this->message_content, '?')) {
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
			$this->say(':8ball: | ' . $yes_list[$yes_random] . ', **' . $this->message_author . '**.');
		} else {
			$this->say(':8ball: | ' . $no_list[$no_random] . ', **' . $this->message_author . '**.');
		}
	}

	public function storepsychopass()
	{
		require_once 'model/database.php';
		$database = new Database;

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

		$message_exploded = explode(' ', $this->message_content);
		foreach ($message_exploded as $word) {
			if (array_key_exists($word, $bad_list)) {
				$amount += $bad_list[$word];
			}
			if (array_key_exists($word, $good_list)) {
				$amount -= $good_list[$word];
			}
		}

		$query = 'SELECT * from users WHERE user_id = ' . $this->MESSAGE->author->id;
		$result = $database->MYSQL->query($query);
		$rows_number = $result->num_rows;

		if ($rows_number == 0) {
			$query = 'INSERT INTO users (username, user_id, discriminator, psychopass) VALUES ("' . $this->message_author . '", "' . $this->MESSAGE->author->id . '", "' . $this->MESSAGE->author->discriminator . '", "0")';
		} else {
			$row = $result->fetch_assoc();
			if (($row['psychopass'] + $amount) < 0) {
				$query = 'UPDATE users SET psychopass = "0" WHERE user_id = "' . $this->MESSAGE->author->id . '"';
			} else {
				$query = 'UPDATE users SET psychopass = psychopass + "' . $amount . '" WHERE user_id = "' . $this->MESSAGE->author->id . '"';
			}
		}

		$result = $database->MYSQL->query($query);
	}

	public function psychopass()
	{
		if (!$this->command_argument) {
			return;
		}

		require_once 'model/database.php';
		$database = new Database;

		$target_userid = str_replace(array('<@', '>'), '', $this->command_argument);
		$query = 'SELECT * FROM users WHERE user_id = ' . $target_userid;
		$result = $database->MYSQL->query($query);
		$rows_number = $result->num_rows;

		if ($rows_number == 0) {
			$this->say('`[SIBYL SYSTEM]: ERROR` The psychopass cannot be determined.');
			return;
		}

		$row = $result->fetch_assoc();
		$psychopass = $row['psychopass'];
		$verdict = '';
		
		if ($psychopass < 100) {
			$verdict = '**[**<@' . $target_userid . '>**] Crime Coefficient is ' . $psychopass . '% -** `Suspect is not a target for enforcement action. The trigger of Dominator will be locked.`';
		} elseif ($psychopass < 300) {
			$verdict = '**[**<@' . $target_userid . '>**] Crime Coefficient is ' . $psychopass . '% -** `Suspect is classified as a latent criminal and is a target for enforcement action. Dominator is set to Non-Lethal Paralyzer mode. Suspect can then be knocked out using the Dominator.`';
		} else {
			$verdict = '**[**<@' . $target_userid . '>**] Crime Coefficient is ' . $psychopass . '% -** `Suspect poses a serious threat to the society. Lethal force is authorized. Dominator will automatically switch to Lethal Eliminator.`';
		}

		$this->say($verdict);
	}
}
?>