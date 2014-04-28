<?php

namespace Gecky\Helper;

use Symfony\Component\Templating\Helper\Helper;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\Matcher\UrlMatcherInterface;
use Symfony\Component\HttpFoundation\Request;

class RouterHelper extends Helper
{
	protected $generator;
	protected $matcher;
	protected $request;
	
	/**
	 * Constructor.
	 *
	 * @param UrlGeneratorInterface $router A Router instance
	 */
	public function __construct(UrlGeneratorInterface $router, UrlMatcherInterface $matcher, Request $request)
	{
		$this->generator = $router;
		$this->matcher = $matcher;
		$this->request = $request;
	}
	
	/**
	 * Generates a URL from the given parameters.
	 *
	 * @param string         $name          The name of the route
	 * @param mixed          $parameters    An array of parameters
	 * @param bool|string    $referenceType The type of reference (one of the constants in UrlGeneratorInterface)
	 *
	 * @return string The generated URL
	 *
	 * @see UrlGeneratorInterface
	 */
	public function generate($name, $parameters = array(), $referenceType = UrlGeneratorInterface::ABSOLUTE_PATH)
	{
		return $this->generator->generate($name, $parameters, $referenceType);
	}
	
	/**
	 * Check if the current request is on the specified route name
	 *
	 * @param string         $routeName          The name of the route
	 *
	 * @return bool true if on the specified route, otherwise false 
	 */
	public function onRoute($routeName)
	{
		$parameters = $this->matcher->match($this->request->getPathInfo());
    	
    	return $parameters['_route'] == $routeName;
	}
	
	/**
	 * Returns the canonical name of this helper.
	 *
	 * @return string The canonical name
	 */
	public function getName()
	{
		return 'router';
	}
}