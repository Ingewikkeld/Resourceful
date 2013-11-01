<?php

namespace Ingewikkeld\Rest\UserBundle\ResourceMapper;

use Hal\Resource;
use Ingewikkeld\Rest\Resource\MapperInterface;
use Ingewikkeld\Rest\UserBundle\Entity\User as UserEntity;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Translation\TranslatorInterface;
use FOS\UserBundle\Model\UserManagerInterface;

class User implements MapperInterface
{
    /** @var UserManagerInterface $userManager */
    protected $userManager;

    /** @var TranslatorInterface $translator */
    protected $translator;

    /** @var RouterInterface $router */
    protected $router;

    /**
     * Initializes this user mapper.
     *
     * @param UserManagerInterface $userManager
     * @param TranslatorInterface  $translator
     * @param RouterInterface      $router
     */
    public function __construct(
        UserManagerInterface $userManager,
        TranslatorInterface $translator,
        RouterInterface $router
    ) {
        $this->userManager = $userManager;
        $this->translator  = $translator;
        $this->router      = $router;
    }

    /**
     *
     *
     * @param string|integer $identifier
     *
     * @throws NotFoundHttpException if the user could not be found
     *
     * @return Resource
     */
    public function getResource($identifier)
    {
        $user = $this->userManager->findUserByUsername($identifier);
        if (!$user) {
            throw new NotFoundHttpException(
                $this->translator->trans('error.user_not_found', array('%id%' => $identifier))
            );
        }

        return $this->createResourceFromUser($user);
    }

    public function getCollection(array $options = array())
    {
        /** @var UserEntity[] $collection */
        $collection = $this->userManager->findUsers();
        $resource   = new Resource($this->generateBrowseUrl(), array('count' => count($collection)));

        foreach ($collection as $element) {
            $resource->setEmbedded('user', $this->createResourceFromUser($element));
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
            'ingewikkeld_rest_user_user_browse',
            array(),
            UrlGeneratorInterface::ABSOLUTE_PATH
        );
    }

    /**
     * Generate the URL for the read page for the given resource.
     *
     * @param Resource $resource
     *
     * @return string
     */
    public function generateReadUrl($resourceOrIdentifier)
    {
        if ($resourceOrIdentifier instanceof Resource) {
            $data = $resourceOrIdentifier->toArray();
            $id = $data['username'];
        } else {
            $id = $resourceOrIdentifier;
        }

        $route = $this->router->generate(
            'ingewikkeld_rest_user_user_read',
            array('id' => $id),
            UrlGeneratorInterface::ABSOLUTE_PATH
        );

        return $route;
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
        $formData = $form->getData();

        $user = $this->userManager->createUser();
        $user->setUsername($formData['username']);
        $user->setEmail($formData['email']);
        $user->setPlainPassword($formData['password']);
        $user->setEnabled(true);
        $this->userManager->updateUser($user);

        return $this->createResourceFromUser($user);
    }

    /**
     * Persists the resource to the storage engine.
     *
     * @param Resource $resource
     *
     * @throws NotFoundHttpException if no user with the given id could be found.
     *
     * @return null
     */
    public function update(Resource $resource)
    {
        $data = $resource->toArray();

        $user = $this->userManager->findUserByUsername($data['username']);
        if (!$user) {
            $errorMessage = $this->translator->trans('error.user_not_found', array('%username%' => $data['username']));
            throw new NotFoundHttpException($errorMessage);
        }

        $user->setUsername($data['username']);
        $user->setEmail($data['email']);
        $user->setPlainPassword($data['password']);
        $user->setEnabled(true);
        $this->userManager->updateUser($user);
    }

    /**
     * Removes the User from the database.
     *
     * @param Resource $resource
     *
     * @throws NotFoundHttpException if no user with the given id could be found.
     *
     * @return void
     */
    public function delete(Resource $resource)
    {
        $data = $resource->toArray();

        $user = $this->userManager->findUserByUsername($data['username']);
        if (!$user) {
            $errorMessage = $this->translator->trans('error.user_not_found', array('%username%' => $data['username']));
            throw new NotFoundHttpException($errorMessage);
        }

        $this->userManager->deleteUser($user);
    }

    public function populateResourceWithForm(Resource $resource, FormInterface $form)
    {
        $formData = $form->getData();

        $resource->setData(
             array(
                 'username' => $formData['username'],
                 'email'    => $formData['email']
             )
        );
    }

    /**
     *
     *
     * @param UserEntity $user
     *
     * @return Resource
     */
    protected function createResourceFromUser(UserEntity $user)
    {
        $resource = new Resource(
            $this->generateReadUrl($user->getUsernameCanonical()),
            array(
                 'username'   => $user->getUsernameCanonical(),
                 'email'      => $user->getEmail(),
                 'last_login' => $user->getLastLogin() ? $user->getLastLogin()->format('c') : null,
            )
        );

        return $resource;
    }
}
