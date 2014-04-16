<?php

namespace Gecky\Console;

use Symfony\Component\Console\Application as BaseApplication;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader as DIYamlLoader;
use Symfony\Component\Routing\Loader\YamlFileLoader as RYamlLoader;
use Symfony\Component\Config\FileLocator;

class Application extends BaseApplication
{
	protected $container;
	
	public function __construct($name = 'UNKNOWN', $version = 'UNKNOWN')
	{
		//Load routes
		$routesLoader = new RYamlLoader(new FileLocator('config/'));
		$routes = $routesLoader->load('routes.yml');
		
		//Load services
		$this->container = new ContainerBuilder();
		$this->container->register('matcher', 'Symfony\Component\Routing\Matcher\UrlMatcher')
			->setArguments(array($routes, new Reference('context')));
		$this->container->register('routing.generator', 'Symfony\Component\Routing\Generator\UrlGenerator')
			->setArguments(array($routes, new Reference('context')));
		$loader = new DIYamlLoader($this->container, new FileLocator('config/'));
		$loader->load('parameters.yml');
		$loader->load('services.yml');
		
		return parent::__construct($name, $version);	
	}
	
	public function getContainer()
	{
		return $this->container;	
	}

    /**
     * Gets the default commands that should always be available.
     *
     * @return array An array of default Command instances
     */
    protected function getDefaultCommands()
    {
        // Keep the core default commands to have the HelpCommand
        // which is used when using the --help option
        $defaultCommands = parent::getDefaultCommands();

        //$defaultCommands[] = new MyCommand();

        return $defaultCommands;
    }
}