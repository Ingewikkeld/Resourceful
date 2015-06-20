<?php

namespace Ingewikkeld\ResourcefulBundle\EventListener;

use FOS\RestBundle\Util\StopFormatListenerException;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\Exception\NotAcceptableHttpException;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use FOS\RestBundle\Util\FormatNegotiatorInterface;
use FOS\RestBundle\Util\MediaTypeNegotiatorInterface;

/**
 * This listener handles Accept header format negotiations.
 *
 * This is an adaptation of the FormatListener of the FosRestBundle to support variable mime-types whose format is set.
 * So for example: `vnd.ingewikkeld.net+xml` is not recognized by Symfony as a valid format because it is configured
 * in the config.yml and hence would normally not be accepted but with the changes in this listener we will allow it (if
 * no other format matches) and use the `+xml` to determine that this is an XML format.
 *
 * This does mean that it is up to the controller
 *
 * @author Lukas Kahwe Smith <smith@pooteeweet.org>
 * @author Mike van Riel <me@mikevanriel.com>
 */
class FormatListener
{
    private $formatNegotiator;

    /**
     * Initialize FormatListener.
     *
     * @param FormatNegotiatorInterface $formatNegotiator
     */
    public function __construct(FormatNegotiatorInterface $formatNegotiator)
    {
        $this->formatNegotiator = $formatNegotiator;
    }

    /**
     * Determines and sets the Request format
     *
     * @param GetResponseEvent $event The event
     *
     * @throws NotAcceptableHttpException
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
        try {
            $request = $event->getRequest();
            $format = $request->getRequestFormat(null);
            if (null === $format) {
                if ($this->formatNegotiator instanceof MediaTypeNegotiatorInterface) {
                    $mediaType = $this->formatNegotiator->getBestMediaType($request);
                    if ($mediaType) {
                        $request->attributes->set('media_type', $mediaType);
                        $format = $request->getFormat($mediaType);
                        if ($format === null) {
                            $mediaType = explode('+', $mediaType);
                            if (isset($mediaType[1]) && $request->getMimeType($mediaType[1])) {
                                $format = $mediaType[1];
                            }
                        }
                    }
                } else {
                    $format = $this->formatNegotiator->getBestFormat($request);
                }
            }

            if (null === $format) {
                if ($event->getRequestType() === HttpKernelInterface::MASTER_REQUEST) {
                    throw new NotAcceptableHttpException("No matching accepted Response format could be determined");
                }

                return;
            }

            $request->setRequestFormat($format);
        } catch (StopFormatListenerException $e) {
            // nothing to do
        }

    }
}
