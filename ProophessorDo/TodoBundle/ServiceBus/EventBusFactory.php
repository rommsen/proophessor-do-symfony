<?php
namespace Prooph\ProophessorDo\TodoBundle\ServiceBus;

use Prooph\ProophessorDo\Model\Todo\Event\DeadlineWasAddedToTodo;
use Prooph\ProophessorDo\Model\Todo\Event\ReminderWasAddedToTodo;
use Prooph\ProophessorDo\Model\Todo\Event\TodoAssigneeWasReminded;
use Prooph\ProophessorDo\Model\Todo\Event\TodoWasMarkedAsDone;
use Prooph\ProophessorDo\Model\Todo\Event\TodoWasPosted;
use Prooph\ProophessorDo\Model\Todo\Event\TodoWasReopened;
use Prooph\ProophessorDo\Model\User\Event\UserWasRegistered;
use Prooph\ServiceBus\EventBus;
use Prooph\ServiceBus\Plugin\InvokeStrategy\OnEventStrategy;
use Prooph\ServiceBus\Plugin\Router\EventRouter;
use Symfony\Component\DependencyInjection\ContainerInterface;

class EventBusFactory
{
    /**
     * @var ContainerInterface
     */
    private $container;
 
    public function __invoke(ContainerInterface $container)
    {
        $this->container = $container;
        $eventBus = new EventBus();

        $eventBus->utilize(new OnEventStrategy());
        $eventBus->utilize(new ServiceLocatorPlugin($container));
        $this->attachRouter($eventBus);

        return $eventBus;
    }

    private function attachRouter(EventBus $eventBus)
    {
        $router = new EventRouter();

        $userProjector = 'prooph.proophessor_do.todo.projection.user_projector';
        $todoProjector = 'prooph.proophessor_do.todo.projection.todo_projector';
        $todoReminderProjector = 'prooph.proophessor_do.todo.projection.todo_reminder_projector';

        $router->route(UserWasRegistered::class)
            ->to($userProjector);

        $router->route(TodoWasPosted::class)
            ->to($todoProjector)
            ->andTo($userProjector);

        $router->route(TodoWasMarkedAsDone::class)
            ->to($todoProjector)
            ->andTo($userProjector);

        $router->route(TodoWasReopened::class)
            ->to($todoProjector)
            ->andTo($userProjector);

        $router->route(DeadlineWasAddedToTodo::class)
            ->to($todoProjector);

        $router->route(ReminderWasAddedToTodo::class)
            ->to($todoProjector)
            ->andTo($todoReminderProjector);

        $router->route(TodoAssigneeWasReminded::class)
            ->to($todoProjector)
            ->andTo($todoReminderProjector);

        $eventBus->utilize($router);
    }
}