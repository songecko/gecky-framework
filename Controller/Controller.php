<?php

namespace Gecky\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Templating\PhpEngine;
use Symfony\Component\Templating\TemplateNameParser;
use Symfony\Component\Templating\Loader\FilesystemLoader;
use Symfony\Component\DependencyInjection\ContainerAware;

class Controller extends ContainerAware
{
	protected $templating;
	
	public function __construct()
	{
		$loader = new FilesystemLoader($this->getViewsDir());		
		$this->templating = new PhpEngine(new TemplateNameParser(), $loader);		
	}
	
	protected function render($name, array $parameters = array())
	{
		$view = $this->templating->render('layout.php', array(
			'content' => $this->templating->render($name, $parameters),
			'container' => $this->container 
		));
		return new Response($view);
	}
	
	protected function getViewsDir()
	{
		throw new \Exception("Must implement getViewsDir()");
	}
}