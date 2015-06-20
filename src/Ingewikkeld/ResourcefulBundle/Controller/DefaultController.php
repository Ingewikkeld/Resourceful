<?php

namespace Ingewikkeld\ResourcefulBundle\Controller;

use Ingewikkeld\ResourcefulBundle\ApiKernel;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController
{
    /** @var ApiKernel */
    private $apiKernel;

    public function __construct(ApiKernel $apiKernel)
    {
        $this->apiKernel = $apiKernel;
    }

    public function handleAction(Request $request)
    {
        $response = new Response($this->apiKernel->handle($request));
        $response->headers->set('Content-Type', $request->attributes->get('media_type'));
        
        return $response;
    }
}
