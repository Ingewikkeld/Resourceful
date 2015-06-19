<?php

namespace Ingewikkeld\ResourcefulBundle\Commands;

use Ingewikkeld\ResourcefulBundle\ProjectionFactory;

class Projector
{
    /**
     * @var ProjectionFactory
     */
    private $projectionFactory;

    public function __construct(ProjectionFactory $projectionFactory)
    {
        $this->projectionFactory = $projectionFactory;
    }

    public function handle(ProjectionCommand $projection)
    {
        $projectionObject = $this->projectionFactory->get($projection->getProjectionName());

        return $projectionObject->project($projection->getUrl(), $projection->getOptions());
    }
}
