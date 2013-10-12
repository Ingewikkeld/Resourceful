<?php

namespace Ingewikkeld\Rest\UserBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Translation\Translator;
use Hal\Resource;
use Ingewikkeld\Rest\UserBundle\Entity\User;
use Ingewikkeld\Rest\UserBundle\Entity\UserRepository;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use FOS\RestBundle\Controller\Annotations\Get;

/**
 * @Route("/user", service="ingewikkeld_rest_user.controller.default")
 */
class DefaultController
{
    /** @var Translator $translator */
    protected $translator;

    /** @var UserRepository $userRepository */
    protected $userRepository;

    /**
     * Initializes this controller with a translator and appropriate repository.
     *
     * @param Translator     $translator
     * @param UserRepository $userRepository
     */
    public function __construct(Translator $translator, UserRepository $userRepository)
    {
        $this->translator     = $translator;
        $this->userRepository = $userRepository;
    }

    /**
     * @Get("/")
     */
    public function indexAction()
    {
        $resource = new Resource('/api/user');

        /** @var User[] $users */
        $users = $this->userRepository->findAll();
        foreach ($users as $user) {
            $resource->setEmbedded('user', $this->createUserResource($user));
        }

        return new Response((string)$resource);
    }

    /**
     * @Get("/{username}")
     */
    public function readAction(Request $request)
    {
        /** @var User $user */
        $user = $this->userRepository->findOneByUsername($request->get('username'));
        if (!$user) {
            throw new NotFoundHttpException(
                $this->translator->trans('error.user_not_found', array('%username%' => $request->get('username')))
            );
        }

        $resource = $this->createUserResource($user);

        return new Response((string)$resource);
    }

    /**
     * Create a Resource to be returned by the API based on a User entity.
     *
     * @param User $user
     *
     * @return Resource
     */
    protected function createUserResource(User $user)
    {
        $resource = new Resource(
            '/api/user/' . $user->getUsername(),
            array(
                'username'   => $user->getUsername(),
                'email'      => $user->getEmail(),
                'last_login' => $user->getLastLogin()->format('c'),
            )
        );

        return $resource;
    }
}
