<?php
namespace Ingewikkeld\ResourcefulBundle\DependencyInjection;

use Ingewikkeld\ResourcefulBundle\EventListener\FormatListener;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class OverrideFormatListenerCompilerPass implements CompilerPassInterface
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
        $definition = $container->getDefinition('fos_rest.format_listener');
        $definition->setClass(FormatListener::class);
    }
}
