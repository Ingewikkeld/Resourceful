<?php

namespace Ingewikkeld\ResourcefulBundle;

use Ingewikkeld\ResourcefulBundle\DependencyInjection\CommandCompilerPass;
use Ingewikkeld\ResourcefulBundle\DependencyInjection\ProjectionCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class IngewikkeldResourcefulBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new ProjectionCompilerPass());
        $container->addCompilerPass(new CommandCompilerPass());
    }
}
