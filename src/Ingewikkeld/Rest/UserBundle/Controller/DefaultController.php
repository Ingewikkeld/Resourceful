<?php

namespace Ingewikkeld\Rest\UserBundle\Controller;

use Hal\Resource;
use Ingewikkeld\Rest\UserBundle\Entity\User;
use Ingewikkeld\Rest\UserBundle\Entity\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations\Get;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Translation\Translator;

/**
 * @Route("/user", service="ingewikkeld_rest_user.controller.default")
 */
class DefaultController
{
    protected $translator;

    protected $userRepository;

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
        $users = $this->getRepository()->findAll();
        foreach ($users as $user) {
            $resource->setEmbedded('user', $this->createUserResource($user));
        }

        return new Response((string)$resource);
    }

    /**
     * @Get("/{username}")
     */
    public function readAction($username)
    {
        /** @var User $user */
        $user = $this->getRepository()->findOneByUsername($username);
        if (!$user) {
            throw new NotFoundHttpException(
                $this->getTranslator()->trans('error.user_not_found', array('%username%' => $username))
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

    /**
     * Returns the repository for the user object.
     *
     * @return UserRepository
     */
    protected function getRepository()
    {
        return $this->userRepository;
    }

    /**
     * Returns the translation object with which to Translate strings.
     *
     * @return Translator
     */
    protected function getTranslator()
    {
        return $this->translator;
    }
}
