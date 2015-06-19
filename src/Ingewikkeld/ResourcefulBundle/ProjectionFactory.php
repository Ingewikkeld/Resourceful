<?php

namespace Ingewikkeld\ResourcefulBundle;

class ProjectionFactory
{
    private $projections = [];

    public function __construct(array $projections = [])
    {
        $this->projections = $projections;
    }

    public function addProjection($name, Projection $projection)
    {
        $this->projections[$name] = $projection;
    }

    /**
     * @param string $projectionName
     *
     * @return null|Projection
     */
    public function get($projectionName)
    {
        if (! isset($this->projections[$projectionName])) {
            return null;
        }

        $service = $this->projections[$projectionName];
        if (! $service instanceof Projection) {
            return null;
        }

        return $service;
    }
}
