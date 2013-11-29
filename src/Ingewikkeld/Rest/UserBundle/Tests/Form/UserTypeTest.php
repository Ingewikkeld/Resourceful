<?php
/**
 * Symfony REST Edition.
 *
 * @link      https://github.com/Ingewikkeld/symfony-rest-edition
 * @copyright Copyright (c) 2013-2013 Ingewikkeld<info@ingewikkeld.net>
 * @license   https://github.com/Ingewikkeld/symfony-rest-edition/blob/master/LICENSE MIT License
 */

namespace Ingewikkeld\Rest\UserBundle\Tests\Form;

use Ingewikkeld\Rest\UserBundle\Form\UserType;
use Mockery as m;

/**
 * Tests the form type for the User Resource.
 */
class UserTypeTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Tests whether all expected input is added to the Form.
     *
     * @covers Ingewikkeld\Rest\UserBundle\Form\UserType::buildForm
     */
    public function testCreatesAllFields()
    {
        $builderMock = m::mock('Symfony\Component\Form\FormBuilderInterface');
        $builderMock->shouldReceive('add')->with('username', 'text', m::any())->andReturn($builderMock);
        $builderMock->shouldReceive('add')->with('email', 'email', m::any())->andReturn($builderMock);
        $builderMock->shouldReceive('add')->with('password', 'password', m::any())->andReturn($builderMock);

        $type = new UserType();
        $type->buildForm($builderMock, array());

        $this->assertTrue(true);
    }

    /**
     * Tests whether the form has still got the same name.
     *
     * @covers Ingewikkeld\Rest\UserBundle\Form\UserType::getName
     */
    public function testReturnsTheCorrectName()
    {
        $fixture = new UserType();

        $this->assertSame('user', $fixture->getName());
    }
}
