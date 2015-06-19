<?php

namespace Ingewikkeld\ResourcefulBundle;

use Symfony\Component\HttpFoundation\Request;

interface Command
{
    public static function fromRequest(Request $request);
}
