<?php
/*
 * This file is part of the prooph/event-store.
 * (c) 2014-2015 prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Date: 10/21/15 - 6:10 PM
 */
namespace ProophessorDo\TodoBundle\Repository;

use Prooph\EventStore\Aggregate\AggregateRepository;
use Prooph\EventStore\Aggregate\AggregateType;
use Prooph\EventStore\Exception\ConfigurationException;
use Prooph\ProophessorDo\Model\Todo\Todo;
use Symfony\Component\DependencyInjection\ContainerInterface;


class EventStoreTodoListFactory
{

    /**
     * @param ContainerInterface $container
     * @throws ConfigurationException
     * @return AggregateRepository
     */
    public function __invoke(ContainerInterface $container)
    {
        $eventStore = $container->get('proophessor_do_todo.infrastructure.event_store');
        $aggregateType = AggregateType::fromAggregateRootClass(Todo::class);
        $aggregateTranslator = $container->get('proophessor_do_todo.infrastructure.aggregate_translator');
        $snapshotStore = null;
        $streamName = null;
        $oneStreamPerAggregate = false;

        return new EventStoreTodoList(
            $eventStore,
            $aggregateType,
            $aggregateTranslator,
            $snapshotStore,
            $streamName,
            $oneStreamPerAggregate
        );

    }
}
