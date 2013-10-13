<?php

namespace Ingewikkeld\Rest\UserBundle\Controller;

use FOS\UserBundle\Model\UserManagerInterface;
use Ingewikkeld\Rest\UserBundle\Form\UserType;
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
use Ingewikkeld\Rest\UserBundle\Entity\User;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\Put;
use FOS\RestBundle\Controller\Annotations\Delete;

/**
 * @Route("/user", service="ingewikkeld_rest_user.controller.user")
 */
class UserController
{
    /** @var TranslatorInterface $translator */
    protected $translator;

    /** @var FormFactoryInterface $formFactory */
    protected $formFactory;

    /** @var UserManagerInterface */
    protected $userManager;

    /** @var RouterInterface */
    protected $router;

    /**
     * Initializes this controller with a translator and Doctrine EntityManager.
     *
     * @param TranslatorInterface  $translator
     * @param FormFactoryInterface $formFactory
     * @param UserManagerInterface $userManager
     * @param RouterInterface      $router
     */
    public function __construct(
        TranslatorInterface  $translator,
        FormFactoryInterface $formFactory,
        UserManagerInterface $userManager,
        RouterInterface      $router
    ) {
        $this->translator    = $translator;
        $this->formFactory   = $formFactory;
        $this->userManager   = $userManager;
        $this->router        = $router;
    }

    /**
     * @Get("/", name="ingewikkeld_rest_user_browse")
     */
    public function browseAction()
    {
        /** @var User[] $collection */
        $collection = $this->userManager->findUsers();

        $resource = new Resource(
            $this->generateUrl('ingewikkeld_rest_user_browse'),
            array('count' => count($collection))
        );

        foreach ($collection as $element) {
            $resource->setEmbedded('user', $this->createResource($element));
        }

        return new Response((string)$resource);
    }

    /**
     * @Get("/{username}", name="ingewikkeld_rest_user_read")
     */
    public function readAction(Request $request)
    {
        /** @var User $user */
        $user = $this->userManager->findUserByUsername($request->get('username'));
        if (!$user) {
            throw new NotFoundHttpException(
                $this->translator->trans('error.user_not_found', array('%username%' => $request->get('username')))
            );
        }

        return new Response((string)$this->createResource($user));
    }

    /**
     * @param Request $request
     *
     * @Put("/{username}", name="ingewikkeld_rest_user_edit")
     */
    public function editAction(Request $request)
    {
        $form = $this->createForm();
        $form->submit($request->request->all());
        if (!$form->isValid()) {
            throw new BadRequestHttpException($form->getErrorsAsString());
        }

        $user = $this->userManager->findUserByUsername($request->get('username'));
        $user->setUsername($request->get('username'));
        $user->setEmail($request->get('email'));
        $user->setPlainPassword($request->get('password'));
        $user->setEnabled(true);
        $this->userManager->updateUser($user);

        return new Response();
    }

    /**
     * @param Request $request
     *
     * @Post("/", name="ingewikkeld_rest_user_add")
     */
    public function addAction(Request $request)
    {
        $form = $this->createForm();
        $form->submit($request->request->all());
        if (!$form->isValid()) {
            throw new BadRequestHttpException($form->getErrorsAsString());
        }

        $user = $this->userManager->createUser();
        $user->setUsername($request->get('username'));
        $user->setEmail($request->get('email'));
        $user->setPlainPassword($request->get('password'));
        $user->setEnabled(true);
        $this->userManager->updateUser($user);

        return new Response(
            '',
            201,
            array(
                 'Location' => $this->generateUrl(
                     'ingewikkeld_rest_user_read', array('username' => $user->getUsernameCanonical())
                 )
            )
        );
    }

    /**
     * @param Request $request
     *
     * @Delete("/{username}", name="ingewikkeld_rest_user_delete")
     */
    public function deleteAction(Request $request)
    {
        $user = $this->userManager->findUserByUsername($request->get('username'));
        if (!$user) {
            throw new NotFoundHttpException(
                $this->translator->trans('error.user_not_found', array('%username%' => $request->get('username')))
            );
        }

        $this->userManager->deleteUser($user);

        return new Response('', 204);
    }

    /**
     * Create a Resource to be returned by the API based on a User entity.
     *
     * @param User $user
     *
     * @return Resource
     */
    protected function createResource(User $user)
    {
        $resource = new Resource(
            $this->generateUrl('ingewikkeld_rest_user_read', array('username' => $user->getUsernameCanonical())),
            array(
                'username'   => $user->getUsername(),
                'email'      => $user->getEmail(),
                'last_login' => $user->getLastLogin() ? $user->getLastLogin()->format('c') : null,
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
        return $this->formFactory->create(new UserType());
    }
}
