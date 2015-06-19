<?php

namespace Ingewikkeld\Resourceful\PrototypeBundle\Projections;

use Hal\Resource;
use Ingewikkeld\Resourceful\PrototypeBundle\Projection;

class Show implements Projection
{
    public function project($linkToSelf, array $options = [])
    {
        return new Resource($linkToSelf);
    }
}
