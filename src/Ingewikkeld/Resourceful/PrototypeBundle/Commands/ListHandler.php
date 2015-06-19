<?php

namespace Ingewikkeld\Resourceful\PrototypeBundle\Commands;

class ListHandler
{
    public function handle(ListCommand $command)
    {
        $response = new \Hal\Resource(
            '/api',
            [
                'items' => [
                    'a',
                    'b',
                    'c',
                ]
            ]
        );

        return $response;
    }
}
