<?php
require_once 'config.php';

class Database
{
	private $DB_HOST;
	private $DB_USER;
	private $DB_PASSWORD;
	private $DB_DATABASE;

	public $MYSQL;

	public function __construct()
	{
		$this->DB_HOST = DB_HOST;
		$this->DB_USER = DB_USER;
		$this->DB_PASSWORD = DB_PASSWORD;
		$this->DB_DATABASE = DB_DATABASE;

		$this->MYSQL = new mysqli($this->DB_HOST, $this->DB_USER, $this->DB_PASSWORD, $this->DB_DATABASE);
	}
}
?>