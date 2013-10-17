<?php

namespace Ingewikkeld\Rest\OAuthServerBundle\Resource\Factory;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Hal\Resource as HalResource;
use Ingewikkeld\Rest\OAuthServerBundle\Entity\Client as ClientEntity;
use Ingewikkeld\Rest\OAuthServerBundle\Resource\Mapper\Client as ClientMapper;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Translation\TranslatorInterface;

class Client
{
    protected $entityManager;
    protected $router;
    protected $translator;

    public function __construct(EntityManager $entityManager, RouterInterface $router, TranslatorInterface $translator)
    {
        $this->entityManager = $entityManager;
        $this->router        = $router;
        $this->translator    = $translator;
    }

    /**
     *
     *
     * @param string|integer $identifier
     *
     * @throws NotFoundHttpException if the client could not be found
     *
     * @return HalResource
     */
    public function getResource($identifier)
    {
        /** @var ClientEntity $client */
        $client = $this->getRepository()->findOneBy(array('id' => $identifier));
        if (!$client) {
            throw new NotFoundHttpException(
                $this->translator->trans('error.client_not_found', array('%id%' => $identifier))
            );
        }

        $mapper = new ClientMapper($this->entityManager, $this->translator, $this);
        return $mapper->createResourceFromObjects(array('client' => $client));
    }

    public function getCollection($options = array())
    {
        /** @var ClientEntity[] $collection */
        $collection = $this->getRepository()->findAll();
        $mapper     = new ClientMapper($this->entityManager, $this->translator, $this);
        $resource   = new HalResource($this->generateBrowseUrl(), array('count' => count($collection)));

        foreach ($collection as $element) {
            $resource->setEmbedded('client', $mapper->createResourceFromObjects(array('client' => $element)));
        }

        return $resource;
    }

    /**
     * Generates the URL for browsing the collection of resources.
     *
     * @return string
     */
    public function generateBrowseUrl()
    {
        return $this->router->generate(
            'ingewikkeld_rest_oauth_server_client_browse',
            array(),
            UrlGeneratorInterface::RELATIVE_PATH
        );
    }

    /**
     * Generate the URL for the read page for the given resource.
     *
     * @param HalResource $resource
     *
     * @return string
     */
    public function generateReadUrl($resourceOrIdentifier)
    {
        if ($resourceOrIdentifier instanceof HalResource) {
            $data = $resourceOrIdentifier->getData();
            $id = $data['id'];
        } else {
            $id = $resourceOrIdentifier;
        }

        return $this->router->generate(
            'ingewikkeld_rest_oauth_server_client_read',
            array('id' => $id),
            UrlGeneratorInterface::RELATIVE_PATH
        );
    }

    /**
     * @return EntityRepository
     */
    protected function getRepository()
    {
        return $this->entityManager->getRepository('IngewikkeldRestOAuthServerBundle:Client');
    }
}
