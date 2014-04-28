<?php

namespace Gecky;

use Symfony\Component\HttpKernel\HttpKernel;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader as DIYamlLoader;
use Symfony\Component\Routing\Loader\YamlFileLoader as RYamlLoader;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
 
class Framework extends HttpKernel
{
	//public function __construct(EventDispatcherInterface $dispatcher, ControllerResolverInterface $resolver, $debug = false)
	public function __construct($debug = false)
	{					
		if ($debug)
		{
			ini_set('display_errors', 1);
			error_reporting(-1);
		}
		
		//Load routes
		$routesLoader = new RYamlLoader(new FileLocator('config/'));
		$routes = $routesLoader->load('routes.yml');
		
		//Load services
		$container = new ContainerBuilder();
		$container->register('matcher', 'Symfony\Component\Routing\Matcher\UrlMatcher')
			->setArguments(array($routes, new Reference('context')));
		$container->register('routing.generator', 'Symfony\Component\Routing\Generator\UrlGenerator')
			->setArguments(array($routes, new Reference('context')));		
		$loader = new DIYamlLoader($container, new FileLocator('config/'));
		$loader->load('parameters.yml');
		$loader->load('services.yml');
		
		return parent::__construct($container->get('dispatcher'), $container->get('resolver'));
	}
	
	public function handle(Request $request, $type = HttpKernelInterface::MASTER_REQUEST, $catch = true)
	{
		$session = new Session();
		$session->start();
		$request->setSession($session);
		
		return parent::handle($request, $type, $catch);
	}
	
}