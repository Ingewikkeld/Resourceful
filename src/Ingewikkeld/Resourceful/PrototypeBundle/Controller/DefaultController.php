<?php

namespace Ingewikkeld\Resourceful\PrototypeBundle\Controller;

use Hal\Resource;
use Ingewikkeld\Resourceful\PrototypeBundle\Command;
use Ingewikkeld\Resourceful\PrototypeBundle\Commands\ProjectionCommand;
use League\Tactician\CommandBus;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\UnsupportedMediaTypeHttpException;

class DefaultController
{
    /** @var CommandBus */
    private $commandBus;

    public function __construct(CommandBus $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    public function handleAction($command = null, $projection = null, array $allowedMimeTypes = ['*'], Request $request)
    {
        list($mimeType, $format) = $this->getMimeTypeAndFormatFromAcceptHeader($request);

        $foundMimeType = false;
        foreach ($allowedMimeTypes as $allowedMimeType) {
            if ($allowedMimeType == '*' || $mimeType === $allowedMimeType) {
                $foundMimeType = true;
            }
        }
        if (!$foundMimeType) {
            throw new UnsupportedMediaTypeHttpException();
        }

        $resource = null;
        if ($command) {
            if (! in_array(Command::class, class_implements($command))) {
                throw new HttpException(500, 'The command "' . $command . '" does not implement the Command interface');
            }
            /** @var Command $command */
            $resource = $this->commandBus->handle($command::fromRequest($request));
        }

        if ($projection) {
            $resource = $this->commandBus->handle(
                new ProjectionCommand($request->getRequestUri(), $projection, $request->request->all())
            );
        }

        if (! $resource) {
            throw new HttpException(
                500,
                'A Route that need to be handled by Resourceful needs to feature either the "command" or "projection" '
                . 'parameter.'
            );
        }

        $response = $this->convertResourceToFormat($resource, $format);

        return new Response($response);
    }

    /**
     * @param Request $request
     *
     * @return array
     */
    private function getMimeTypeAndFormatFromAcceptHeader(Request $request)
    {
        $currentMimeType = explode('+', $request->headers->get('Accept'));
        if (! isset($currentMimeType[1])) {
            $currentMimeType[1] = 'json';
        }

        return $currentMimeType;
    }

    /**
     * @param $format
     * @param $resource
     *
     * @return string
     */
    private function convertResourceToFormat(Resource $resource, $format)
    {
        switch ($format) {
            case 'xml':
                $response = $resource->getXML()->asXML();
                break;
            default:
                $response = (string)$resource;

                return $response;
        }

        return $response;
    }
}
