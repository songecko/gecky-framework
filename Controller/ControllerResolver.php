<?php

namespace Gecky\Controller;

use Symfony\Component\HttpKernel\Controller\ControllerResolver as BaseControllerResolver;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Psr\Log\LoggerInterface;

/**
 * ControllerResolver.
 */
class ControllerResolver extends BaseControllerResolver
{
    protected $container;

    /**
     * Constructor.
     *
     * @param ContainerInterface   $container A ContainerInterface instance
     */
    public function __construct(ContainerInterface $container, LoggerInterface $logger = null)
    {
        $this->container = $container;

        parent::__construct($logger);
    }

    /**
     * Returns a callable for the given controller.
     *
     * @param string $controller A Controller string
     *
     * @return mixed A PHP callable
     *
     * @throws \InvalidArgumentException
     */
    protected function createController($controller)
    {
        list($controller, $method) = parent::createController($controller);

        if ($controller instanceof ContainerAwareInterface) {
            $controller->setContainer($this->container);
        }

        return array($controller, $method);
    }
}