<?php
/**
 * RestDistribution
 */

namespace Ingewikkeld\Rest\OAuthServerBundle\Resource\Mapper;

use Doctrine\ORM\EntityManager;
use Hal\Resource;
use Ingewikkeld\Rest\OAuthServerBundle\Entity\Client as ClientEntity;
use Ingewikkeld\Rest\OAuthServerBundle\Resource\Factory\Client as Factory;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Translation\TranslatorInterface;

class Client
{
    /** @var EntityManager $entityManager */
    protected $entityManager;

    /** @var TranslatorInterface $translator */
    protected $translator;

    /** @var Factory $factory */
    protected $factory;

    /**
     * Initializes this client mapper.
     *
     * @param EntityManager       $entityManager
     * @param TranslatorInterface $translator
     * @param Factory             $factory
     */
    public function __construct(
        EntityManager $entityManager,
        TranslatorInterface $translator,
        Factory $factory
    ) {
        $this->entityManager = $entityManager;
        $this->translator    = $translator;
        $this->factory       = $factory;
    }

    /**
     * Creates a new Resource from the given parameters.
     *
     * @param string[] $parameters
     *
     * @return Resource
     */
    public function create(array $parameters)
    {
        $client = new ClientEntity();

        $client->setRedirectUris($parameters['redirectUris']);
        $client->setAllowedGrantTypes($parameters['grants']);

        $this->entityManager->persist($client);
        $this->entityManager->flush();

        return $this->createResourceFromObjects(array('client' => $client));
    }

    /**
     * Persists the resource to the storage engine.
     *
     * @param Resource $resource
     *
     * @throws NotFoundHttpException if no client with the given id could be found.
     *
     * @return null
     */
    public function update(Resource $resource)
    {
        $data = $resource->toArray();

        /** @var ClientEntity $client */
        $client = $this->getRepository()->findOneBy(array('id' => $data['id']));
        if (!$client) {
            $errorMessage = $this->translator->trans('error.client_not_found', array('%id%' => $data['id']));
            throw new NotFoundHttpException($errorMessage);
        }

        $client->setRedirectUris($data['redirectUris']);
        $client->setAllowedGrantTypes($data['grants']);

        $this->entityManager->persist($client);
        $this->entityManager->flush();
    }

    /**
     * Removes the Client from the database.
     *
     * @param Resource $resource
     *
     * @throws NotFoundHttpException if no client with the given id could be found.
     */
    public function delete(Resource $resource)
    {
        $data = $resource->toArray();

        $client = $this->getRepository()->findOneBy(array('id' => $data['id']));
        if (!$client) {
            $errorMessage = $this->translator->trans('error.client_not_found', array('%id%' => $data['id']));
            throw new NotFoundHttpException($errorMessage);
        }

        $this->entityManager->remove($client);
    }

    /**
     *
     *
     * @param object[] $objects
     *
     * @return Resource
     */
    public function createResourceFromObjects(array $objects)
    {
        /** @var ClientEntity $client */
        $client = $objects['client'];

        $resource = new Resource(
            $this->factory->generateReadUrl($client->getId()),
            array(
                 'id'           => $client->getId(),
                 'publicId'     => $client->getPublicId(),
                 'secret'       => $client->getSecret(),
                 'redirectUris' => $client->getRedirectUris(),
                 'grants'       => $client->getAllowedGrantTypes(),
            )
        );

        return $resource;
    }

    /**
     * @return \Doctrine\ORM\EntityRepository
     */
    protected function getRepository()
    {
        return $this->entityManager->getRepository('IngewikkeldRestOAuthServerBundle:Client');
    }
}
