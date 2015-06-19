<?php

namespace Ingewikkeld\ResourcefulBundle;

interface Projection
{
    public function project($linkToSelf, array $options = []);
}
