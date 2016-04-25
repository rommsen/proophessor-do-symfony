<?php
namespace Prooph\ProophessorDo\TodoBundle;

use Prooph\ProophessorDo\Model\User\Command\RegisterUser;
use Prooph\ProophessorDo\Model\User\EmailAddress;
use Prooph\ProophessorDo\Model\User\UserId;
use Symfony\Component\DependencyInjection\ContainerInterface;

class Test
{
    public function __construct(ContainerInterface $container)
    {
        $commandBus = $container->get('prooph.proophessor_do.todo.service_bus.command_bus');

        $commandBus->dispatch(
            RegisterUser::withData(
                UserId::generate()->toString(),
                'Roman Sachse',
                EmailAddress::fromString('roman.sachse@googlemail.com')->toString()
            )
        );
    }
}