<?php
namespace Ingewikkeld\ResourcefulBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * This compiler pass maps Handler DI tags to specific commands
 */
class CommandCompilerPass implements CompilerPassInterface
{
    /**
     * You can modify the container here before it is dumped to PHP code.
     *
     * @param ContainerBuilder $container
     *
     * @throws \Exception
     * @api
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->has('tactician.handler.locator.symfony')) {
            throw new \Exception('Missing tactician.handler.locator.symfony definition');
        }

        $handlerLocator = $container->findDefinition('tactician.handler.locator.symfony');

        $mapping = [];

        foreach ($container->findTaggedServiceIds('resourceful.command') as $id => $tags) {
            foreach ($tags as $attributes) {
                if (!isset($attributes['message'])) {
                    throw new \Exception('The resourceful.command tag must always have a message attribute');
                }

                $mapping[$attributes['message']] = $id;
            }
        }

        $handlerLocator->addArgument($mapping);
    }

}
