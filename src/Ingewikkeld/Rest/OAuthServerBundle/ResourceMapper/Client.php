<?php
/**
 * Symfony REST Edition.
 *
 * @link      https://github.com/Ingewikkeld/symfony-rest-edition
 * @copyright Copyright (c) 2013-2013 Ingewikkeld
 * @license   https://github.com/Ingewikkeld/symfony-rest-edition/blob/master/LICENSE MIT License
 */

namespace Ingewikkeld\Rest\OAuthServerBundle\ResourceMapper;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Hal\Resource;
use Ingewikkeld\Rest\OAuthServerBundle\Entity\Client as ClientEntity;
use Ingewikkeld\Rest\Resource\MapperInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Translation\TranslatorInterface;

class Client implements MapperInterface
{
    /** @var EntityManager $entityManager */
    protected $entityManager;

    /** @var TranslatorInterface $translator */
    protected $translator;

    /** @var RouterInterface $router */
    protected $router;

    /**
     * Initializes this client mapper.
     *
     * @param EntityManager       $entityManager
     * @param TranslatorInterface $translator
     * @param RouterInterface     $router
     */
    public function __construct(
        EntityManager $entityManager,
        TranslatorInterface $translator,
        RouterInterface $router
    ) {
        $this->entityManager = $entityManager;
        $this->translator    = $translator;
        $this->router        = $router;
    }

    /**
     * Returns the oAuth2 Client with the given identifier.
     *
     * @param string|integer $identifier
     *
     * @throws NotFoundHttpException if the client could not be found.
     *
     * @return Resource
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

        return $this->createResourceFromClient($client);
    }

    /**
     * Returns all clients in a single collection resource.
     *
     * @param string[] $options
     *
     * @return Resource
     */
    public function getCollection(array $options = array())
    {
        /** @var ClientEntity[] $collection */
        $collection = $this->getRepository()->findAll();
        $resource   = new Resource($this->generateBrowseUrl(), array('count' => count($collection)));

        foreach ($collection as $element) {
            $resource->setEmbedded('client', $this->createResourceFromClient($element));
        }

        return $resource;
    }

    /**
     * Creates a new Resource from the given parameters.
     *
     * @param FormInterface $form
     *
     * @return Resource
     */
    public function create(FormInterface $form)
    {
        $client = new ClientEntity();

        $formData = $form->getData();

        $client->setRedirectUris($formData['redirectUris']);
        $client->setAllowedGrantTypes($formData['grants']);

        $this->entityManager->persist($client);
        $this->entityManager->flush();

        return $this->createResourceFromClient($client);
    }

    /**
     * Persists the resource to the storage engine.
     *
     * @param \Hal\Resource $resource
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
     * @param \Hal\Resource $resource
     *
     * @throws NotFoundHttpException if no client with the given id could be found.
     *
     * @return void
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
     * Populates the given resource with the values in the form; overwriting any existing items.
     *
     * @param \Hal\Resource $resource
     * @param FormInterface $form
     *
     * @return void
     */
    public function populateResourceWithForm(Resource $resource, FormInterface $form)
    {
        $formData = $form->getData();

        $resource->setData(
             array(
                 'redirectUris' => $formData['redirectUris'],
                 'grants'       => $formData['grants']
             )
        );
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
            UrlGeneratorInterface::ABSOLUTE_PATH
        );
    }

    /**
     * Generate the URL for the read page for the given resource.
     *
     * @param Resource|string|int $resourceOrIdentifier
     *
     * @return string
     */
    public function generateReadUrl($resourceOrIdentifier)
    {
        if ($resourceOrIdentifier instanceof Resource) {
            $data = $resourceOrIdentifier->toArray();
            $id = $data['id'];
        } else {
            $id = $resourceOrIdentifier;
        }

        $route = $this->router->generate(
            'ingewikkeld_rest_oauth_server_client_read',
            array('id' => $id),
            UrlGeneratorInterface::ABSOLUTE_PATH
        );

        return $route;
    }

    /**
     * Creates a new resource from the given Client entity.
     *
     * @param ClientEntity $client
     *
     * @return Resource
     */
    protected function createResourceFromClient(ClientEntity $client)
    {
        $resource = new Resource(
            $this->generateReadUrl($client->getId()),
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
     * Finds and returns the Doctrine EntityRepository associated with the Client.
     *
     * @return EntityRepository
     */
    protected function getRepository()
    {
        return $this->entityManager->getRepository('IngewikkeldRestOAuthServerBundle:Client');
    }
}
