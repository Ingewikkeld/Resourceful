<?php

namespace Ingewikkeld\Rest\OAuthServerBundle\Controller;

use Hal\Resource;
use Ingewikkeld\Rest\OAuthServerBundle\Resource\Mapper;
use Ingewikkeld\Rest\OAuthServerBundle\Resource\Factory\Client as Factory;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Ingewikkeld\Rest\OAuthServerBundle\Form\ClientType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\Put;
use FOS\RestBundle\Controller\Annotations\Delete;

/**
 * @Route("/client", service="ingewikkeld_rest_oauth_server.controller.client")
 */
class ClientController
{
    /** @var FormFactoryInterface $formFactory */
    protected $formFactory;

    /** @var Factory */
    protected $resourceFactory;

    /** @var Mapper\Client  */
    protected $mapper;

    /**
     * Initializes this controller with a translator and Doctrine EntityManager.
     *
     * @param FormFactoryInterface $formFactory
     * @param Factory              $resourceFactory
     * @param Mapper\Client        $mapper
     */
    public function __construct(
        FormFactoryInterface $formFactory,
        Factory              $resourceFactory,
        Mapper\Client        $mapper
    ) {
        $this->formFactory     = $formFactory;
        $this->resourceFactory = $resourceFactory;
        $this->mapper          = $mapper;
    }

    /**
     * @Get("/", name="ingewikkeld_rest_oauth_server_client_browse")
     */
    public function browseAction()
    {
        return new Response((string)$this->resourceFactory->getCollection());
    }

    /**
     * @Get("/{id}", name="ingewikkeld_rest_oauth_server_client_read")
     */
    public function readAction(Request $request)
    {
        return new Response((string)$this->resourceFactory->getResource($request->get('id')));
    }

    /**
     * @param Request $request
     *
     * @Put("/{username}", name="ingewikkeld_rest_oauth_server_client_edit")
     */
    public function editAction(Request $request)
    {
        $formData = $this->validateAddAndEditRequestParameters($request);

        $resource = $this->resourceFactory->getResource($request->get('id'));
        $resource->setData(
            array(
                'redirectUris' => $formData['redirectUris'],
                'grants'       => $formData['grants']
            )
        );

        $this->mapper->update($resource);

        // returns an empty 200 (OK)
        return new Response();
    }

    /**
     * @param Request $request
     *
     * @Post("/", name="ingewikkeld_rest_oauth_server_client_add")
     */
    public function addAction(Request $request)
    {
        $resource = $this->mapper->create($this->validateAddAndEditRequestParameters($request));

        return new Response('', 201, array('Location' => $this->resourceFactory->generateReadUrl($resource)));
    }

    /**
     * @param Request $request
     *
     * @Delete("/{id}", name="ingewikkeld_rest_oauth_server_client_delete")
     */
    public function deleteAction(Request $request)
    {
        $resource = $this->resourceFactory->getResource($request->get('id'));

        $this->mapper->delete($resource);

        return new Response('', 204);
    }

    /**
     *
     *
     * @param Request $request
     *
     * @throws BadRequestHttpException
     *
     * @return FormInterface
     */
    protected function validateAddAndEditRequestParameters(Request $request)
    {
        $data = $request->request->all();
        if ($data['id']) {
            unset($data['id']);
        }

        // we explicitly do not load existing data; an edit is a PUT and should supply all valid data!
        $form = $this->formFactory->create(new ClientType());
        $form->submit($data);
        if (!$form->isValid()) {
            throw new BadRequestHttpException($form->getErrorsAsString());
        }

        return $form->getData();
    }
}
