<?php

namespace Ingewikkeld\Rest\OAuthServerBundle\Controller;

use FOS\OAuthServerBundle\Model\Client;
use Ingewikkeld\Rest\OAuthServerBundle\Resource\Client as ClientResource;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Translation\TranslatorInterface;
use Hal\Resource;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\Put;
use FOS\RestBundle\Controller\Annotations\Delete;

/**
 * @Route("/user", service="ingewikkeld_rest_oauth_server_client.controller.default")
 */
class DefaultController
{
    /** @var TranslatorInterface $translator */
    protected $translator;

    /** @var FormFactoryInterface $formFactory */
    protected $formFactory;

    /** @var ClientResource $clientResource */
    protected $clientResource;

    /** @var RouterInterface */
    protected $router;

    /**
     * Initializes this controller with a translator and Doctrine EntityManager.
     *
     * @param TranslatorInterface  $translator
     * @param FormFactoryInterface $formFactory
     * @param ClientResource        $clientResource
     * @param RouterInterface      $router
     */
    public function __construct(
        TranslatorInterface  $translator,
        FormFactoryInterface $formFactory,
        ClientResource       $clientResource,
        RouterInterface      $router
    ) {
        $this->translator     = $translator;
        $this->formFactory    = $formFactory;
        $this->clientResource = $clientResource;
        $this->router         = $router;
    }

    /**
     * @Get("/", name="ingewikkeld_rest_oauth_server_client_browse")
     */
    public function browseAction()
    {
        /** @var Client[] $collection */
        $collection = $this->clientResource->findAll();

        $resource = new Resource(
            $this->generateUrl('ingewikkeld_rest_oauth_server_client_browse'),
            array('count' => count($collection))
        );

        foreach ($collection as $element) {
            $resource->setEmbedded('client', $this->createResource($element));
        }

        return new Response((string)$resource);
    }

    /**
     * @Get("/{id}", name="ingewikkeld_rest_oauth_server_client_read")
     */
    public function readAction(Request $request)
    {
        /** @var Client $client */
        $client = $this->clientResource->findBy('id', $request->get('id'));
        if (!$client) {
            throw new NotFoundHttpException(
                $this->translator->trans('error.client_not_found', array('%id%' => $request->get('id')))
            );
        }

        return new Response((string)$this->createResource($client));
    }

    /**
     * @param Request $request
     *
     * @Put("/{username}", name="ingewikkeld_rest_oauth_server_client_edit")
     */
    public function editAction(Request $request)
    {
        $form = $this->createForm();
        $form->submit($request->request->all());
        if (!$form->isValid()) {
            throw new BadRequestHttpException($form->getErrorsAsString());
        }

        $client = $this->clientResource->findById($request->get('id'));
//        $user->setUsername($request->get('username'));
//        $user->setEmail($request->get('email'));
//        $user->setPlainPassword($request->get('password'));
//        $user->setEnabled(true);
        $this->clientResource->update($client);

        return new Response();
    }

    /**
     * @param Request $request
     *
     * @Post("/", name="ingewikkeld_rest_oauth_server_client_add")
     */
    public function addAction(Request $request)
    {
        $form = $this->createForm();
        $form->submit($request->request->all());
        if (!$form->isValid()) {
            throw new BadRequestHttpException($form->getErrorsAsString());
        }

        $client = $this->clientResource->create();
//        $user->setUsername($request->get('username'));
//        $user->setEmail($request->get('email'));
//        $user->setPlainPassword($request->get('password'));
//        $user->setEnabled(true);
        $this->clientResource->update($client);

        return new Response(
            '',
            201,
            array(
                 'Location' => $this->generateUrl(
                     'ingewikkeld_rest_userbundle_read', array('id' => $client->getId())
                 )
            )
        );
    }

    /**
     * @param Request $request
     *
     * @Delete("/{id}", name="ingewikkeld_rest_oauth_server_client_delete")
     */
    public function deleteAction(Request $request)
    {
        $user = $this->clientResource->findBy('id', $request->get('id'));
        if (!$user) {
            throw new NotFoundHttpException(
                $this->translator->trans('error.client_not_found', array('%username%' => $request->get('username')))
            );
        }

        $this->clientResource->deleteUser($user);

        return new Response('', 204);
    }

    /**
     * Create a Resource to be returned by the API based on a entity.
     *
     * @param Client $client
     *
     * @return Resource
     */
    protected function createResource(Client $client)
    {
        $resource = new Resource(
            $this->generateUrl('ingewikkeld_rest_oauth_server_client_read', array('id' => $client->getId())),
            array(
//                'username'   => $client->getUsername(),
//                'email'      => $client->getEmail(),
//                'last_login' => $client->getLastLogin() ? $client->getLastLogin()->format('c') : null,
            )
        );

        return $resource;
    }

    /**
     * Generates a, by default relative, URL given a routename.
     *
     * @param string   $routeName     The name of the route for which to generate a URL.
     * @param string[] $parameters    A list of parameters to use in the URL.
     * @param string   $referenceType What type of URL to generate, one of the constants in {@see UrlGeneratorInterface}.
     *
     * @return string
     */
    protected function generateUrl(
        $routeName,
        $parameters = array(),
        $referenceType = UrlGeneratorInterface::RELATIVE_PATH
    ) {
        return $this->router->generate($routeName, $parameters, $referenceType);
    }

    /**
     * Creates a new form to use with the user.
     *
     * @return FormInterface
     */
    protected function createForm()
    {
        return $this->formFactory->create(new ClientType());
    }
}
