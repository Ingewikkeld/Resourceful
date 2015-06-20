<?php

namespace Ingewikkeld\ResourcefulBundle;

use Hal\Resource;
use Ingewikkeld\ResourcefulBundle\Commands\ProjectionCommand;
use League\Tactician\CommandBus;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\UnsupportedMediaTypeHttpException;

class ApiKernel
{
    /** @var CommandBus */
    private $commandBus;

    public function __construct(CommandBus $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    /**
     * @param Request $request
     *
     * @return string
     */
    public function handle(Request $request)
    {
        $accepts    = $request->attributes->get('accepts');
        $command    = $request->attributes->get('command');
        $projection = $request->attributes->get('projection');

        list($mimeType, $format) = $this->getMimeTypeAndFormatFromAcceptHeader($request);

        if (is_string($accepts)) {
            $accepts = [
                [ 'mime_type' => $accepts, 'projection' => $projection ]
            ];
        }

        $foundMimeType = false;
        foreach ($accepts as $acceptCriteria) {
            $allowedMimeType = current(explode('+', $acceptCriteria['mime_type']));
            if ($allowedMimeType == '*' || $mimeType === $allowedMimeType) {
                $foundMimeType = true;
                $projection = $acceptCriteria['projection'];
                break;
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

        return $this->convertResourceToFormat($resource, $format);
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
