<?php

namespace Gecky;

use Symfony\Component\HttpKernel\HttpKernel;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpKernel\Controller\ControllerResolverInterface;
 
class Framework extends HttpKernel
{
	public function __construct(EventDispatcherInterface $dispatcher, ControllerResolverInterface $resolver, $debug = false)
	{			
		if ($debug)
		{
			ini_set('display_errors', 1);
			error_reporting(-1);
		}
		
		return parent::__construct($dispatcher, $resolver);
	}
	
}