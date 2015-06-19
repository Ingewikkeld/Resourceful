<?php

namespace Ingewikkeld\Resourceful\PrototypeBundle;

use Symfony\Component\DependencyInjection\ContainerInterface;

class ProjectionFactory
{
    /** @var ContainerInterface */
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param string $projectionName
     *
     * @return null|Projection
     */
    public function get($projectionName)
    {
        if (! $this->container->has($projectionName)) {
            return null;
        }

        $service = $this->container->get($projectionName);
        if (! $service instanceof Projection) {
            return null;
        }

        return $service;
    }
}
