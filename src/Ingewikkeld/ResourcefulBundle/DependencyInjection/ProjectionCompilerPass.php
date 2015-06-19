<?php


namespace Ingewikkeld\ResourcefulBundle\DependencyInjection;


use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class ProjectionCompilerPass implements CompilerPassInterface
{
    const SERVICE_PROJECTION_FACTORY = 'resourceful.projection_factory';

    public function process(ContainerBuilder $container)
    {
        if (!$container->has(self::SERVICE_PROJECTION_FACTORY)) {
            return;
        }

        $factoryDefinition = $container->findDefinition(self::SERVICE_PROJECTION_FACTORY);

        $taggedServices = $container->findTaggedServiceIds('resourceful.projection');
        foreach ($taggedServices as $id => $tags) {
            foreach($tags as $attributes) {
                $factoryDefinition->addMethodCall('addProjection', array($attributes['alias'], new Reference($id)));
            }
        }
    }
}
