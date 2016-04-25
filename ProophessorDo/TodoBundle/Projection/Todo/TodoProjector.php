<?php
/*
 * This file is part of prooph/proophessor.
 * (c) 2014-2015 prooph software GmbH <contact@prooph.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Date: 5/4/15 - 5:44 PM
 */
namespace Prooph\ProophessorDo\TodoBundle\Projection\Todo;

use Doctrine\DBAL\Connection;
use Prooph\ProophessorDo\Model\Todo\Event\DeadlineWasAddedToTodo;
use Prooph\ProophessorDo\Model\Todo\Event\ReminderWasAddedToTodo;
use Prooph\ProophessorDo\Model\Todo\Event\TodoWasMarkedAsDone;
use Prooph\ProophessorDo\Model\Todo\Event\TodoWasPosted;
use Prooph\ProophessorDo\Model\Todo\Event\TodoWasReopened;
use Prooph\ProophessorDo\TodoBundle\Projection\Table;

/**
 * Class TodoProjector
 *
 * @package Prooph\ProophessorDo\TodoBundle\Projection\Todo
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 */
final class TodoProjector
{
    /**
     * @var Connection
     */
    private $connection;

    /**
     * @param Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @param TodoWasPosted $event
     */
    public function onTodoWasPosted(TodoWasPosted $event)
    {
        $this->connection->insert(Table::TODO, [
            'id' => $event->todoId()->toString(),
            'assignee_id' => $event->assigneeId()->toString(),
            'text' => $event->text(),
            'status' => $event->todoStatus()->toString()
        ]);
    }

    /**
     * @param TodoWasMarkedAsDone $event
     */
    public function onTodoWasMarkedAsDone(TodoWasMarkedAsDone $event)
    {
        $this->connection->update(Table::TODO,
            [
                'status' => $event->newStatus()->toString()
            ],
            [
                'id' => $event->todoId()->toString()
            ]
        );
    }

    /**
     * @param TodoWasReopened $event
     */
    public function onTodoWasReopened(TodoWasReopened $event)
    {
        $this->connection->update(Table::TODO,
            [
                'status' => $event->status()->toString()
            ],
            [
                'id' => $event->todoId()->toString()
            ]
        );
    }

    /**
     * @param DeadlineWasAddedToTodo $event
     * @return void
     */
    public function onDeadlineWasAddedToTodo(DeadlineWasAddedToTodo $event)
    {
        $this->connection->update(
            Table::TODO,
            [
                'deadline' => $event->deadline()->toString(),
            ],
            [
                'id' => $event->todoId()->toString(),
            ]
        );
    }

    /**
     * @param ReminderWasAddedToTodo $event
     * @return void
     */
    public function onReminderWasAddedToTodo(ReminderWasAddedToTodo $event)
    {
        $this->connection->update(
            Table::TODO,
            [
                'reminder' => $event->reminder()->toString(),
            ],
            [
                'id' => $event->todoId()->toString(),
            ]
        );
    }
}
