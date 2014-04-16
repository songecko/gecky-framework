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

class Controller extends ContainerAware
{
	protected $templating;
	
	public function __construct()
	{
		$loader = new FilesystemLoader($this->getViewsDir());		
		$this->templating = new PhpEngine(new TemplateNameParser(), $loader);
		
		$request = Request::createFromGlobals();		
		$this->templating->set(new AssetsHelper($request->getBasePath()));
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
	
	protected function redirect($url)
	{
		return new RedirectResponse($url);
	}
	
	protected function generateUrl($routeName)
	{
		return $this->container->get('routing.generator')->generate($routeName);
	}
	
	protected function getViewsDir()
	{
		throw new \Exception("Must implement getViewsDir()");
	}
}