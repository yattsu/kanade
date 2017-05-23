<?php
require_once 'config.php';

class Banan
{
	public $MESSAGE;
	public $BOT_USERNAME;
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
		$victim_chance = 30;
		$regular_chance = 3;
		
		if (in_array($this->message_author, $victim)) {
			echo '>>>[CHANCE]: ' . $victim_chance . '%/' . $chance . "%\n\n";
			if ($chance <= $victim_chance) {
				$this->reply($harrassments[$random_harrass]);
			}
		}

		if (!in_array($this->message_author, $safe_users) && $this->message_author !== $victim && $this->message_author !== $this->BOT_USERNAME) {
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
		$this->say('you');
	}

	public function selfdefense()
	{
		$this->say('i have less than 50 words in my vocabulary and i\'m still smarter than you');
	}

	public function gj()
	{
		$this->say('thanks master');
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
}
?>