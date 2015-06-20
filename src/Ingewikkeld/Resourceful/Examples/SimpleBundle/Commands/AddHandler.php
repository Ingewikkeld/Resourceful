<?php

namespace Ingewikkeld\Resourceful\Examples\SimpleBundle\Commands;

class AddHandler
{
    public function handle(AddCommand $command)
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
