<?php

namespace Prooph\ProophessorDo\TodoBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class ProophessorDoTodoExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('command_handler.xml');
        $loader->load('projection.xml');
        $loader->load('prooph.xml');
        $loader->load('service_bus.xml');
        $loader->load('repository.xml');
    }

    public function getAlias()
    {
        return 'proophessor_do_todo';
    }
}
