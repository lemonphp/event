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

use Lemon\Event\Event;

/**
 * Test class for Event.
 */
class EventTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Lemon\Event\Event
     */
    protected $event;

    /**
     * Sets up the fixture
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->event = new Event('event.test');
    }

    /**
     * Tears down the fixture
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
        $this->event = null;
    }

    /**
     * Test Event::isPropagationStopped() method
     */
    public function testIsPropagationStopped()
    {
        $this->assertFalse($this->event->isPropagationStopped());
    }

    /**
     * Test Event::stopPropagation() method
     */
    public function testStopPropagationAndIsPropagationStopped()
    {
        $this->event->stopPropagation();
        $this->assertTrue($this->event->isPropagationStopped());
    }

    /**
     * Test Event::getEventType() method
     */
    public function testGetEventType()
    {
        $this->assertSame('event.test', $this->event->getEventType());
    }
}
