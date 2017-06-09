<?php
require_once 'config.php';

class Kanade
{
	public $MESSAGE;
	public $BOT_USERNAME;
	private $MASTER_USERNAME;
	private $MASTER_ID;
	private $COMMAND_KEYWORD;
	private $ALLOWED_USERS;
	private $ALLOWED_COMMANDS;
	private $API;

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
		$this->API = API;

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

	public function getguildid()
	{
		$guild_id = $this->MESSAGE->channel->guild->id;

		return $guild_id;
	}

	public function execute_passive($names = [])
	{
		foreach ($names as $name) {
			require 'commands/passive/' . $name . '.php';
		}
	}

	public function execute_active($name)
	{
		require 'commands/active/' . $name . '.php';
	}
}
?>