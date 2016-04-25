<?php
namespace ProophessorDo\TodoBundle\Infrastructure;

use Doctrine\DBAL\Connection;
use Prooph\Common\Event\ProophActionEventEmitter;
use Prooph\Common\Messaging\FQCNMessageFactory;
use Prooph\Common\Messaging\NoOpMessageConverter;
use Prooph\EventStore\Adapter\Doctrine\DoctrineEventStoreAdapter;
use Prooph\EventStore\Adapter\PayloadSerializer\JsonPayloadSerializer;
use Prooph\EventStore\EventStore;
use Symfony\Component\DependencyInjection\ContainerInterface;

class EventStoreFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $eventStore = new EventStore(
            $this->createAdapter($this->getConnection($container)),
            new ProophActionEventEmitter()
        );

        $container->get('proophessor_do_todo.infrastructure.event_publisher')->setUp($eventStore);

        return $eventStore;
    }

    /**
     * @param Connection $connection
     * @return DoctrineEventStoreAdapter
     */
    private function createAdapter(Connection $connection)
    {
        return new DoctrineEventStoreAdapter(
            $connection,
            new FQCNMessageFactory(),
            new NoOpMessageConverter(),
            new JsonPayloadSerializer()
        );
    }

    /**
     * @param ContainerInterface $container
     * @return Connection
     */
    private function getConnection(ContainerInterface $container)
    {
        return $container->get('doctrine.dbal.default_connection');
    }
}