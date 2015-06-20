<?php

namespace Ingewikkeld\Resourceful\Examples\SimpleBundle\Commands;

use Ingewikkeld\ResourcefulBundle\Command;
use Symfony\Component\HttpFoundation\Request;

class AddCommand implements Command
{
    public static function fromRequest(Request $request)
    {
        return new self();
    }
}
