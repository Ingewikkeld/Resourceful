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
        $username  = 'mvriel';
        $email     = 'mike.vanriel@naenius.com';
        $lastLogin = new \DateTime('2014-01-01');
        $readUrl   = 'mvriel.json';

        $user = m::mock('Ingewikkeld\Rest\UserBundle\Entity\User');
        $user->shouldReceive('getUsernameCanonical')->andReturn($username);
        $user->shouldReceive('getEmail')->andReturn($email);
        $user->shouldReceive('getLastLogin')->andReturn($lastLogin);

        $this->userManagerMock->shouldReceive('findUserByUsername')->with($username)->andReturn($user);
        $this->routerMock->shouldReceive('generate')->andReturn($readUrl);

        /** @var \Hal\Resource $resource */
        $resource = $this->fixture->getResource($username);

        $this->assertInstanceOf('Hal\Resource', $resource);
        $this->assertSame(
            $resource->toArray(),
            array(
                '_links' => array(
                    'self' => array ('href' => 'mvriel.json')
                ),
                'username'   => $username,
                'email'      => $email,
                'last_login' => $lastLogin->format('c'),
            )
        );
    }

    /**
     * @covers Ingewikkeld\Rest\UserBundle\ResourceMapper\User::getResource
     * @expectedException Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function testRetrievingANonExistingUserReturns404Exception()
    {
        $username = 'unknownUser';
        $this->userManagerMock->shouldReceive('findUserByUsername')->with($username)->andReturnNull();
        $this->translatorMock->shouldReceive('trans')->with('error.user_not_found', array('%id%' => $username));

        $this->fixture->getResource($username);
    }

    /**
     * @covers Ingewikkeld\Rest\UserBundle\ResourceMapper\User::getCollection
     * @covers Ingewikkeld\Rest\UserBundle\ResourceMapper\User::createResourceFromUser
     */
    public function testRetrievingACollectionOfUserResources()
    {
        $username  = 'mvriel';
        $email     = 'mike.vanriel@naenius.com';
        $lastLogin = new \DateTime('2014-01-01');
        $readUrl   = 'mvriel.json';

        $user = m::mock('Ingewikkeld\Rest\UserBundle\Entity\User');
        $user->shouldReceive('getUsernameCanonical')->andReturn($username);
        $user->shouldReceive('getEmail')->andReturn($email);
        $user->shouldReceive('getLastLogin')->andReturn($lastLogin);

        $this->userManagerMock->shouldReceive('findUsers')->andReturn(array($user));
        $this->routerMock->shouldReceive('generate')->andReturn($readUrl);

        /** @var \Hal\Resource $resource */
        $resource = $this->fixture->getCollection();

        $this->assertInstanceOf('Hal\Resource', $resource);
        $this->assertSame(
            $resource->toArray(),
            array(
                '_links'    => array(
                    'self' => array ('href' => 'mvriel.json')
                ),
                'count'     => 1,
                '_embedded' => array(
                    'user' => array(
                        array(
                            '_links' => array(
                                'self' => array ('href' => 'mvriel.json')
                            ),
                            'username'   => $username,
                            'email'      => $email,
                            'last_login' => $lastLogin->format('c'),
                        )
                    )
                )
            )
        );
    }
}
