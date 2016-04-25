<?php
namespace ProophessorDo\TodoBundle\Infrastructure;

use Prooph\EventStoreBusBridge\TransactionManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

class TransactionManagerFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $transactionManager = new TransactionManager();

        // initialisiere direkt hier mit dem EventStore, da der Weg es in der EventStoreFactory zu
        // machen zu spät ist. Der CommandBus nutzt den TransactionManager und der sagt dann auf
        // dem EventStore beginTransaction. Wenn man es nicht hier macht wird der EventStore erst
        // durch den EventBus initialisiert => zu spät
        $transactionManager->setUp($container->get('proophessor_do_todo.infrastructure.event_store'));

        return $transactionManager;
    }
}