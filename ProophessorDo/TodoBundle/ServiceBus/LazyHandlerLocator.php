<?php
namespace Prooph\ProophessorDo\TodoBundle\ServiceBus;

use Prooph\ProophessorDo\TodoBundle\ServiceBus\Exception\CommandHandlerNotFoundException;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LazyHandlerLocator
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * LazyHandlerLocator constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param $command
     * @return callable
     * @throws CommandHandlerNotFoundException
     */
    public function locate($command)
    {
        return $this->getService($this->getServiceName($command));
    }

    public function getServiceName($command)
    {
        return str_replace(['\\', '_bundle', '_command'], ['.', '', ''], $this->underscore(get_class($command)));
    }

    private function underscore($camelCasedWord)
    {
        return strtolower(preg_replace('/(?<=\\w)([A-Z])/', '_\\1', $camelCasedWord));
    }

    private function getService($service_name)
    {
        if (!$this->container->has($service_name)) {
            throw new CommandHandlerNotFoundException("The Service $service_name is not defined.");
        }

        return $this->container->get($service_name);
    }
}