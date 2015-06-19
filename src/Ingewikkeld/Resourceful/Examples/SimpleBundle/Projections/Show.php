<?php

namespace Ingewikkeld\Resourceful\Examples\SimpleBundle\Projections;

use Hal\Resource;
use Ingewikkeld\ResourcefulBundle\Projection;

class Show implements Projection
{
    public function project($linkToSelf, array $options = [])
    {
        return new Resource($linkToSelf);
    }
}
