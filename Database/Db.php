<?php

namespace Gecky\Database;

class Db {
	
	protected $config;
	protected $connection;
	
	public function __construct($config)
	{
		$this->config = $config;
		
		$this->connect();
	}
	
	public function connect()
	{
		$this->connection = mysql_connect($this->config['host'], $this->config['username'], $this->config['password']);
		mysql_select_db($this->config['database'], $this->connection);
		mysql_query("SET NAMES 'utf8'");
	}
	
	public function execute($query)
	{
		return mysql_query($query);	
	}
	
	public function close()
	{
		mysql_close($this->connection);	
	}
}