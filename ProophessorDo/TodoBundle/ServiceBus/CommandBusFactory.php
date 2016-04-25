<?php
namespace Prooph\ProophessorDo\TodoBundle\ServiceBus;

use Prooph\ServiceBus\CommandBus;
use Prooph\ServiceBus\Plugin\Router\RegexRouter;
use Symfony\Component\DependencyInjection\ContainerInterface;

class CommandBusFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $commandBus = new CommandBus();

        $commandBus->utilize($this->getRouter($container));
        $commandBus->utilize($container->get('proophessor_do_todo.infrastructure.transaction_manager'));

        return $commandBus;
    }

    /**
     * @param ContainerInterface $container
     * @return RegexRouter
     */
    private function getRouter(ContainerInterface $container)
    {
        $handlerLocator = $container->get('prooph.proophessor_do.todo.service_bus.lazy_handler_locator');

        return new RegexRouter([
            RegexRouter::ALL => function ($command) use ($handlerLocator) {
                $handler = $handlerLocator->locate($command);
                $handler($command);
            }
        ]);
    }
}