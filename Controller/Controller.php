<?php

namespace Gecky\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Templating\PhpEngine;
use Symfony\Component\Templating\TemplateNameParser;
use Symfony\Component\Templating\Loader\FilesystemLoader;
use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\Templating\Helper\AssetsHelper;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Gecky\Helper\RouterHelper;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Gecky\Helper\RequestHelper;
use Symfony\Component\HttpFoundation\JsonResponse;

class Controller extends ContainerAware
{
	protected $templating;
	
	public function __construct()
	{
		//Check if we need templating engine
		if($viewsDir = $this->getViewsDir())
		{
			$loader = new FilesystemLoader($viewsDir);		
			$this->templating = new PhpEngine(new TemplateNameParser(), $loader);
			
			$request = Request::createFromGlobals();		
			$this->templating->set(new AssetsHelper($request->getBasePath()));
			$this->templating->set(new RequestHelper($request));
		}
	}
	
	public function setContainer(ContainerInterface $container = null)
	{
		parent::setContainer($container);
		
		if($viewsDir = $this->getViewsDir())
		{
			$request = Request::createFromGlobals();
			$this->templating->set(new RouterHelper($this->container->get('routing.generator'), $this->container->get('matcher'), $request));
		}
	}
	
	protected function render($name, array $parameters = array(), $layout = 'layout')
	{
		$parameters = array_merge($parameters, array(
				'container' => $this->container
		));
		
		$view = $this->templating->render($layout.'.php', array(
			'content' => $this->templating->render($name, $parameters),
			'container' => $this->container 
		));
		
		return new Response($view);
	}
	
	protected function renderJsonResponse($data)
	{
		//TO DO: Hacer más pro.
		return new JsonResponse($data);
	}
	
	protected function redirect($url)
	{
		return new RedirectResponse($url);
	}
	
	protected function generateUrl($routeName, $parameters = array(), $referenceType = UrlGenerator::ABSOLUTE_PATH)
	{
		return $this->container->get('routing.generator')->generate($routeName, $parameters, $referenceType);
	}
	
	protected function get($serviceId)
	{
		return $this->container->get($serviceId);
	}

	protected function getViewsDir()
	{
		//Must implement getViewsDir() to use the templating engine
	}
}