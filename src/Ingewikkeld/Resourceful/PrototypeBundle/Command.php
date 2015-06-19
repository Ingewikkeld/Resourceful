<?php

namespace Ingewikkeld\Resourceful\PrototypeBundle;

use Symfony\Component\HttpFoundation\Request;

interface Command
{
    public static function fromRequest(Request $request);
}
