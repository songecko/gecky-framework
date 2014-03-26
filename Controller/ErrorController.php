<?php

namespace Gecky\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\FlattenException;

class ErrorController extends Controller
{
	public function exceptionAction(FlattenException $exception)
	{
		$msg = 'Something went wrong! ('.$exception->getMessage().')';

		return new Response($msg, $exception->getStatusCode());
	}
	
	protected function getViewsDir()
	{
		return '';
	}
}