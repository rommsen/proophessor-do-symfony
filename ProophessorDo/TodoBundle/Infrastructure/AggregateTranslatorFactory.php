<?php
namespace ProophessorDo\TodoBundle\Infrastructure;

use Prooph\EventSourcing\EventStoreIntegration\AggregateTranslator;
use Symfony\Component\DependencyInjection\ContainerInterface;

class AggregateTranslatorFactory
{

    public function __invoke(ContainerInterface $container)
    {
        return new AggregateTranslator();
    }
}