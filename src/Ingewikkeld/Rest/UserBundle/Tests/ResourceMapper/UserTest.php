<?php
/**
 * Symfony REST Edition.
 *
 * @link      https://github.com/Ingewikkeld/symfony-rest-edition
 * @copyright Copyright (c) 2013-2013 Ingewikkeld<info@ingewikkeld.net>
 * @license   https://github.com/Ingewikkeld/symfony-rest-edition/blob/master/LICENSE MIT License
 */

namespace Ingewikkeld\Rest\UserBundle\Tests\ResourceMapper;

use Ingewikkeld\Rest\UserBundle\ResourceMapper\User;
use Mockery as m;

/**
 * Tests the resource mapper for the User resource.
 */
class UserTest extends \PHPUnit_Framework_TestCase
{
    const TEST_USERNAME = 'mvriel';
    const TEST_EMAIL    = 'mike.vanriel@naenius.com';
    const TEST_SELF_URL = 'mvriel.json';

    /** @var \Mockery\MockInterface|\FOS\UserBundle\Model\UserManagerInterface */
    protected $userManagerMock;

    /** @var \Mockery\MockInterface|\Symfony\Component\Translation\TranslatorInterface */
    protected $translatorMock;

    /** @var \Mockery\MockInterface|\Symfony\Component\Routing\RouterInterface */
    protected $routerMock;

    /** @var User */
    protected $fixture;

    public function setUp()
    {
        $this->userManagerMock = m::mock('FOS\UserBundle\Model\UserManagerInterface');
        $this->translatorMock  = m::mock('Symfony\Component\Translation\TranslatorInterface');
        $this->routerMock      = m::mock('Symfony\Component\Routing\RouterInterface');

        $this->fixture = new User($this->userManagerMock, $this->translatorMock, $this->routerMock);
    }

    /**
     * @covers Ingewikkeld\Rest\UserBundle\ResourceMapper\User::__construct
     */
    public function testCorrectlyRegisterDependencies()
    {
        $userManagerMock = m::mock('FOS\UserBundle\Model\UserManagerInterface');
        $translatorMock  = m::mock('Symfony\Component\Translation\TranslatorInterface');
        $routerMock      = m::mock('Symfony\Component\Routing\RouterInterface');

        $mapper = new User($userManagerMock, $translatorMock, $routerMock);

        $this->assertAttributeSame($userManagerMock, 'userManager', $mapper);
        $this->assertAttributeSame($translatorMock, 'translator', $mapper);
        $this->assertAttributeSame($routerMock, 'router', $mapper);
    }

    /**
     * @covers Ingewikkeld\Rest\UserBundle\ResourceMapper\User::getResource
     * @covers Ingewikkeld\Rest\UserBundle\ResourceMapper\User::createResourceFromUser
     */
    public function testRetrievingAUserResourceByItsUsername()
    {
        $lastLogin = new \DateTime('2014-01-01');

        $user = $this->createUserMock(self::TEST_USERNAME, self::TEST_EMAIL, $lastLogin);
        $this->userManagerMock->shouldReceive('findUserByUsername')->with(self::TEST_USERNAME)->andReturn($user);
        $this->routerMock->shouldReceive('generate')->andReturn(self::TEST_SELF_URL);

        /** @var \Hal\Resource $resource */
        $resource = $this->fixture->getResource(self::TEST_USERNAME);

        $this->assertInstanceOf('Hal\Resource', $resource);
        $this->assertSame($resource->toArray(), $this->createHalArrayForUser($lastLogin));
    }

    /**
     * @covers Ingewikkeld\Rest\UserBundle\ResourceMapper\User::getResource
     * @expectedException Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function testRetrievingANonExistingUserReturns404Exception()
    {
        $this->userManagerMock->shouldReceive('findUserByUsername')->with(self::TEST_USERNAME)->andReturnNull();
        $this->translatorMock
            ->shouldReceive('trans')->with('error.user_not_found', array('%id%' => self::TEST_USERNAME));

        $this->fixture->getResource(self::TEST_USERNAME);
    }

    /**
     * @covers Ingewikkeld\Rest\UserBundle\ResourceMapper\User::getCollection
     * @covers Ingewikkeld\Rest\UserBundle\ResourceMapper\User::createResourceFromUser
     */
    public function testRetrievingACollectionOfUserResources()
    {
        $lastLogin = new \DateTime('2014-01-01');
        $user      = $this->createUserMock(self::TEST_USERNAME, self::TEST_EMAIL, $lastLogin);
        $this->userManagerMock->shouldReceive('findUsers')->andReturn(array($user));
        $this->routerMock->shouldReceive('generate')->andReturn(self::TEST_SELF_URL);

        /** @var \Hal\Resource $resource */
        $resource = $this->fixture->getCollection();

        $this->assertInstanceOf('Hal\Resource', $resource);
        $this->assertSame(
            $resource->toArray(),
            array(
                '_links'    => array('self' => array ('href' => self::TEST_SELF_URL)),
                'count'     => 1,
                '_embedded' => array('user' => array($this->createHalArrayForUser($lastLogin)))
            )
        );
    }

    /**
     * @covers Ingewikkeld\Rest\UserBundle\ResourceMapper\User::create
     */
    public function testCreateNewUserAndReturnResource()
    {
        $user = $this->createUserMock(self::TEST_USERNAME, self::TEST_EMAIL, null)->shouldIgnoreMissing();

        $this->userManagerMock
            ->shouldReceive('createUser')->andReturn($user)
            ->shouldReceive('updateUser')->with($user);

        $formMock = m::mock('Symfony\Component\Form\FormInterface');
        $formMock->shouldReceive('getData')->andReturn(
            array(
                 'username' => self::TEST_USERNAME,
                 'email'    => self::TEST_EMAIL,
                 'password' => 'aPassword'
            )
        );
        $this->routerMock->shouldReceive('generate')->andReturn(self::TEST_SELF_URL);

        $resource = $this->fixture->create($formMock);

        $this->assertInstanceOf('Hal\Resource', $resource);
        $this->assertSame($resource->toArray(), $this->createHalArrayForUser(null));
    }

    /**
     * @covers Ingewikkeld\Rest\UserBundle\ResourceMapper\User::update
     */
    public function testUpdateExistingUser()
    {
        $passwordString = 'aPassword';

        $userArray = array_merge($this->createHalArrayForUser(null), array('password' => $passwordString));
        $userMock  = m::mock('Hal\Resource')->shouldReceive('toArray')->andReturn($userArray)->getMock();

        $userEntity = m::mock('Ingewikkeld\Rest\UserBundle\Entity\User')
            ->shouldReceive('setUsername')->with(self::TEST_USERNAME)->getMock()
            ->shouldReceive('setEmail')->with(self::TEST_EMAIL)->getMock()
            ->shouldReceive('setPlainPassword')->with($passwordString)->getMock()
            ->shouldReceive('setEnabled')->with(true)->getMock();

        $this->userManagerMock
            ->shouldReceive('findUserByUsername')->with(self::TEST_USERNAME)->andReturn($userEntity)
            ->shouldReceive('updateUser')->with($userEntity);

        $this->fixture->update($userMock);
    }

    /**
     * @covers Ingewikkeld\Rest\UserBundle\ResourceMapper\User::populateResourceWithForm
     */
    public function testPopulateResourceWithFormData()
    {
        $formMock = m::mock('Symfony\Component\Form\FormInterface')
            ->shouldReceive('getData')->andReturn(
                array(
                     'username' => self::TEST_USERNAME,
                     'email'    => self::TEST_EMAIL,
                )
            )->getMock();
        $resourceMock = m::mock('Hal\Resource')
            ->shouldReceive('setData')->with(
                array(
                     'username' => self::TEST_USERNAME,
                     'email'    => self::TEST_EMAIL
                )
            )->getMock();

        $this->fixture->populateResourceWithForm($resourceMock, $formMock);
    }

    /**
     * @covers Ingewikkeld\Rest\UserBundle\ResourceMapper\User::delete
     */
    public function testDeleteUserFromDatabase()
    {
        $resourceMock = m::mock('Hal\Resource')
            ->shouldReceive('toArray')->andReturn(array('username' => self::TEST_USERNAME))->getMock();
        $user = $this->createUserMock(self::TEST_USERNAME, self::TEST_EMAIL, null);
        $this->userManagerMock->shouldReceive('findUserByUsername')->with(self::TEST_USERNAME)->andReturn($user);
        $this->userManagerMock->shouldReceive('deleteUser')->with($user);

        $this->fixture->delete($resourceMock);
    }

    /**
     * @covers Ingewikkeld\Rest\UserBundle\ResourceMapper\User::generateBrowseUrl
     */
    public function testGenerateUrlForCollection()
    {
        $this->routerMock
            ->shouldReceive('generate')
            ->with('ingewikkeld_rest_user_user_browse', m::any(), m::any())
            ->andReturn('route');

        $this->assertSame('route', $this->fixture->generateBrowseUrl());
    }

    /**
     * @covers Ingewikkeld\Rest\UserBundle\ResourceMapper\User::generateBrowseUrl
     */
    public function testGenerateUrlForResourceWithResourceObject()
    {
        $resourceMock = m::mock('Hal\Resource')
            ->shouldReceive('toArray')->andReturn(array('username' => self::TEST_USERNAME))->getMock();

        $this->routerMock
            ->shouldReceive('generate')
            ->with('ingewikkeld_rest_user_user_read', array('id' => self::TEST_USERNAME), m::any())
            ->andReturn('route');

        $this->assertSame('route', $this->fixture->generateReadUrl($resourceMock));
    }

    /**
     * @covers Ingewikkeld\Rest\UserBundle\ResourceMapper\User::generateBrowseUrl
     */
    public function testGenerateUrlForResourceWithUsername()
    {
        $this->routerMock
            ->shouldReceive('generate')
            ->with('ingewikkeld_rest_user_user_read', array('id' => self::TEST_USERNAME), m::any())
            ->andReturn('route');

        $this->assertSame('route', $this->fixture->generateReadUrl(self::TEST_USERNAME));
    }

    /**
     * Creates a mock user entity class with pre-filled data.
     *
     * @param string $username
     * @param string $email
     * @param \DateTime $lastLogin
     *
     * @return m\MockInterface|\Ingewikkeld\Rest\UserBundle\Entity\User
     */
    protected function createUserMock($username, $email, $lastLogin)
    {
        return m::mock('Ingewikkeld\Rest\UserBundle\Entity\User')
                 ->shouldReceive('getUsernameCanonical')->andReturn($username)->getMock()
                 ->shouldReceive('getEmail')->andReturn($email)->getMock()
                 ->shouldReceive('getLastLogin')->andReturn($lastLogin)->getMock();
    }

    /**
     * Creates an array representation to test HAL Resources with.
     *
     * @param \DateTime|null $lastLogin
     *
     * @return array
     */
    protected function createHalArrayForUser($lastLogin)
    {
        return array(
            '_links'     => array(
                'self' => array('href' => self::TEST_SELF_URL)
            ),
            'username'   => self::TEST_USERNAME,
            'email'      => self::TEST_EMAIL,
            'last_login' => $lastLogin ? $lastLogin->format('c') : null,
        );
    }
}
