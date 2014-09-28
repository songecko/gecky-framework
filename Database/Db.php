<?php

namespace Gecky\Database;

use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\Driver\YamlDriver;
use Doctrine\ORM\Configuration;
use Doctrine\ORM\Mapping\Driver\SimplifiedYamlDriver;

class Db {
	
	protected $config;
	protected $entityNamespaces;
	protected $entityManager;
	
	public function __construct($config)
	{
		$this->config = $config;
		$this->entityNamespaces = array();
	}
	
	public function addEntityNamespace($namespace)
	{
		$this->entityNamespaces = array_merge($this->entityNamespaces, $namespace);
	}
	
	public function setEntityManager($debug = false)
	{
		$paths = array_keys($this->entityNamespaces);
			
		// the connection configuration
		$dbParams = array(
			'driver'   => 'pdo_mysql',
			'host' => $this->config['host'],
			'user'     => $this->config['username'],
			'password' => $this->config['password'],
			'dbname'   => $this->config['database'],
			'charset' => 'utf8'
		);
		
		$config = Setup::createYAMLMetadataConfiguration($paths, $debug);
		$driver = new SimplifiedYamlDriver($this->entityNamespaces);
		$config->setMetadataDriverImpl($driver);
			
		$this->entityManager = EntityManager::create($dbParams, $config);
	}
	
	/**
	 * @return EntityManager
	 */
	public function getEntityManager()
	{
		if(!$this->entityManager)
		{
			$this->setEntityManager();
		}
		
		return $this->entityManager;
	}
}