<?php

namespace Ingewikkeld\Resourceful\PrototypeBundle;

interface Projection
{
    public function project($linkToSelf, array $options = []);
}
