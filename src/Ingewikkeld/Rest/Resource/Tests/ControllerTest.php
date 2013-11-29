<?php
/**
 * Symfony REST Edition.
 *
 * @link      https://github.com/Ingewikkeld/symfony-rest-edition
 * @copyright Copyright (c) 2013-2013 Ingewikkeld<info@ingewikkeld.net>
 * @license   https://github.com/Ingewikkeld/symfony-rest-edition/blob/master/LICENSE MIT License
 */

namespace Ingewikkeld\Rest\Resource\Tests;

use Ingewikkeld\Rest\Resource\Controller;
use Mockery as m;

class ControllerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers Ingewikkeld\Rest\Resource\Controller::
     */
    public function testSettingUpDependencies()
    {
        $formMock   = m::mock('Symfony\Component\Form\FormInterface');
        $mapperMock = m::mock('Ingewikkeld\Rest\Resource\MapperInterface');

        $fixture = new Controller($formMock, $mapperMock);

        $this->assertAttributeSame($formMock, 'form', $fixture);
        $this->assertAttributeSame($mapperMock, 'mapper', $fixture);
    }
}
