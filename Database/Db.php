<?php

namespace Gecky\Database;

use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\DriverManager;

class Db {
	
	protected $config;
	private $connection;
	
	public function __construct($config)
	{
		$this->config = $config;
	}
	
	/**
	 * @return Doctrine\DBAL\Connection
	 */
	public function getConnection()
	{
		if(!$this->connection)
		{
			$this->connection = DriverManager::getConnection(array(
				'dbname' => $this->config['database'],
				'user' => $this->config['username'],
				'password' => $this->config['password'],
				'host' => $this->config['host'],
				'driver' => 'pdo_mysql',
				'charset' => 'utf8'
			)); 
		}
		
		return $this->connection;
	}
}