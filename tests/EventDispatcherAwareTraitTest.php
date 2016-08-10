<?php
/*
 * This file is part of `lemonphp/event` project.
 *
 * (c) 2015-2016 LemonPHP Team
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Lemon\Event\Tests;

use Lemon\Event\EventDispatcher;

class EventDispatcherAwareTraitTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var PHPUnit_Framework_MockObject_MockObject
     */
    protected $mock;

    /**
     * Sets up the fixture
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->mock = $this->getMockForTrait('Lemon\Event\EventDispatcherAwareTrait');
    }

    /**
     * Tears down the fixture
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
        unset($this->mock);
    }

    public function testSetContainer()
    {
        $dispatcher = new EventDispatcher();
        $this->mock->setEventDispatcher($dispatcher);

        $this->assertSame($dispatcher, $this->getObjectAttribute($this->mock, 'eventDispatcher'));
    }

    public function testGetContainer()
    {
        $dispatcher = new EventDispatcher();
        $this->mock->setEventDispatcher($dispatcher);

        $this->assertSame($dispatcher, $this->mock->getEventDispatcher());
    }

    public function testGetContainerWithoutSet()
    {
        $dispatcher = $this->mock->getEventDispatcher();
        $this->assertNotNull($dispatcher);
        $this->assertInstanceOf('Lemon\Event\EventDispatcher', $dispatcher);
    }
}
