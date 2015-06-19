<?php

namespace Ingewikkeld\Resourceful\PrototypeBundle\Commands;

use Ingewikkeld\Resourceful\PrototypeBundle\Command;
use Symfony\Component\HttpFoundation\Request;

class ListCommand implements Command
{
    public static function fromRequest(Request $request)
    {
        return new self();
    }
}
