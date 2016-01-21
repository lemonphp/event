<?php
/*
 * This file is part of `lemon/event` project.
 *
 * (c) 2015-2016 LemonPHP Team
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Lemon\Event\Tests;

use Lemon\Event\Dispatcher;
use Lemon\Event\Event;
use Lemon\Event\Tests\Stub\EventListeners;

class DispatcherTest extends \PHPUnit_Framework_TestCase
{
    /* Some pseudo events */
    const PRE_FOO  = 'pre.foo';
    const POST_FOO = 'post.foo';
    const PRE_BAR  = 'pre.bar';
    const POST_BAR = 'post.bar';

    /**
     * @var \Lemon\Event\Dispatcher
     */
    protected $dispatcher;

    /**
     * @var \Lemon\Event\Test\Stub\Listeners
     */
    protected $listeners;

    /**
     * Sets up the fixture
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->dispatcher = new Dispatcher();
        $this->listeners  = new EventListeners();
    }

    /**
     * Tears down the fixture
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
        $this->dispatcher = null;
    }

    /**
     * Test initinal state of dispatcher
     */
    public function testInitialState()
    {
        $this->assertSame([], $this->dispatcher->getListeners());
        $this->assertFalse($this->dispatcher->hasListeners(self::PRE_FOO));
        $this->assertFalse($this->dispatcher->hasListeners(self::POST_FOO));
    }

    public function testOn()
    {
        $this->dispatcher->on(self::PRE_FOO, [$this->listeners, 'preFoo']);
        $this->dispatcher->on(self::POST_FOO, [$this->listeners, 'postFoo']);

        $this->assertTrue($this->dispatcher->hasListeners(self::PRE_FOO));
        $this->assertTrue($this->dispatcher->hasListeners(self::POST_FOO));
        $this->assertFalse($this->dispatcher->hasListeners(self::PRE_BAR));
        $this->assertCount(1, $this->dispatcher->getListeners(self::PRE_FOO));
        $this->assertCount(1, $this->dispatcher->getListeners(self::POST_FOO));
        $this->assertCount(2, $this->dispatcher->getListeners());
    }

    public function testOnWithPriority()
    {
        $this->dispatcher->on(self::PRE_FOO, [$this->listeners, 'preFoo'], 10);

        $this->assertTrue($this->dispatcher->hasListeners(self::PRE_FOO));
        $this->assertCount(1, $this->dispatcher->getListeners(self::PRE_FOO));
        $this->assertSame(10, $this->dispatcher->getListenerPriority(self::PRE_FOO, [$this->listeners, 'preFoo']));
    }

    public function testOff()
    {
        $listener1 = new EventListeners();
        $listener2 = new EventListeners();

        $this->dispatcher->on(self::PRE_FOO, [$listener1, 'preFoo']);
        $this->dispatcher->on(self::PRE_FOO, [$listener2, 'preFoo']);
        $this->dispatcher->on(self::PRE_FOO, [$this->listeners, 'preFoo']);
        $this->assertCount(3, $this->dispatcher->getListeners(self::PRE_FOO));

        $this->dispatcher->off(self::PRE_FOO, [$this->listeners, 'preFoo']);
        $this->assertCount(2, $this->dispatcher->getListeners(self::PRE_FOO));
        $this->assertNull($this->dispatcher->getListenerPriority(self::PRE_FOO, [$this->listeners, 'preFoo']));

        $this->dispatcher->off('noevent');
        $this->assertCount(2, $this->dispatcher->getListeners(self::PRE_FOO));

        $this->dispatcher->off(self::PRE_FOO);
        $this->assertFalse($this->dispatcher->hasListeners(self::PRE_FOO));
    }

    public function testGetListenersSortByPriority()
    {
        $listener1 = new EventListeners();
        $listener2 = new EventListeners();
        $listener3 = new EventListeners();
        $this->dispatcher->on(self::PRE_FOO, [$listener1, 'preFoo'], -10);
        $this->dispatcher->on(self::PRE_FOO, [$listener2, 'preFoo'], 10);
        $this->dispatcher->on(self::PRE_FOO, [$listener3, 'preFoo']);

        $expected = [
            [$listener2, 'preFoo'],
            [$listener3, 'preFoo'],
            [$listener1, 'preFoo'],
        ];

        $this->assertSame($expected, $this->dispatcher->getListeners(self::PRE_FOO));
    }

    public function testGetAllListenersSortByPriority()
    {
        $listener1 = new EventListeners();
        $listener2 = new EventListeners();
        $listener3 = new EventListeners();
        $this->dispatcher->on(self::PRE_FOO, [$listener1, 'preFoo'], -10);
        $this->dispatcher->on(self::PRE_FOO, [$listener2, 'preFoo']);
        $this->dispatcher->on(self::PRE_FOO, [$listener3, 'preFoo'], 10);
        $this->dispatcher->on(self::POST_FOO, [$listener1, 'postFoo'], -10);
        $this->dispatcher->on(self::POST_FOO, [$listener2, 'postFoo']);
        $this->dispatcher->on(self::POST_FOO, [$listener3, 'postFoo'], 10);

        $expected = [
            'pre.foo'  => [[$listener3, 'preFoo'], [$listener2, 'preFoo'], [$listener1, 'preFoo']],
            'post.foo' => [[$listener3, 'postFoo'], [$listener2, 'postFoo'], [$listener1, 'postFoo']],
        ];

        $this->assertSame($expected, $this->dispatcher->getListeners());
    }

    public function testGetListenerPriority()
    {
        $listener1 = new EventListeners();
        $listener2 = new EventListeners();
        $this->dispatcher->on(self::PRE_FOO, [$listener1, 'preFoo'], -10);
        $this->dispatcher->on(self::PRE_FOO, [$listener2, 'preFoo']);

        $this->assertSame(-10, $this->dispatcher->getListenerPriority(self::PRE_FOO, [$listener1, 'preFoo']));
        $this->assertSame(0, $this->dispatcher->getListenerPriority(self::PRE_FOO, [$listener2, 'preFoo']));
        $this->assertNull($this->dispatcher->getListenerPriority(self::PRE_BAR, [$listener1, 'preFoo']));
        $this->assertNull($this->dispatcher->getListenerPriority(self::PRE_FOO, function() {}));
    }

    public function testTrigger()
    {
        $this->dispatcher->on(self::PRE_FOO, [$this->listeners, 'preFoo']);
        $this->dispatcher->on(self::POST_FOO, [$this->listeners, 'postFoo']);
        $this->dispatcher->trigger(self::PRE_FOO);
        $event = new Event('test.event');

        $this->assertTrue($this->listeners->preFooInvoked);
        $this->assertFalse($this->listeners->postFooInvoked);
        $this->assertInstanceOf('\Lemon\Event\Event', $this->dispatcher->trigger('noevent'));
        $this->assertInstanceOf('\Lemon\Event\Event', $this->dispatcher->trigger(self::PRE_FOO));
        $this->assertSame($event, $this->dispatcher->trigger($event));
    }

    public function testTriggerForClosure()
    {
        $invoked = '|';
        $listener = function (Event $e) use (&$invoked) {
            $invoked .= ($e->getEventName() . '|');
        };

        $this->dispatcher->on(self::PRE_FOO, $listener);
        $this->dispatcher->on(self::POST_FOO, $listener);
        $this->dispatcher->trigger(self::PRE_FOO);

        $this->assertEquals('|pre.foo|', $invoked);
    }

    public function testTriggerOrderByPriority()
    {
        $invoked = [];
        $listener1 = function() use (&$invoked) {
            $invoked[] = 1;
        };
        $listener2 = function() use (&$invoked) {
            $invoked[] = 2;
        };
        $listener3 = function() use (&$invoked) {
            $invoked[] = 3;
        };

        $this->dispatcher->on(self::PRE_FOO, $listener1, -10);
        $this->dispatcher->on(self::PRE_FOO, $listener2);
        $this->dispatcher->on(self::PRE_FOO, $listener3, 10);
        $this->dispatcher->trigger(self::PRE_FOO);

        $this->assertEquals([3, 2, 1], $invoked);
    }

    public function testStopEventPropagation()
    {
        $otherListener = new EventListeners();

        $this->dispatcher->on(self::POST_FOO, [$this->listeners, 'postFoo'], 10);
        $this->dispatcher->on(self::POST_FOO, [$otherListener, 'postFoo']);
        $this->dispatcher->trigger(self::POST_FOO);

        $this->assertTrue($this->listeners->postFooInvoked);
        $this->assertFalse($otherListener->postFooInvoked);
    }

    public function testListenerArgument()
    {
        $args = [];
        $listener = function() use (&$args) {
            $args = func_get_args();
        };
        $event = new Event(self::PRE_FOO);

        $this->dispatcher->on(self::PRE_FOO, $listener);
        $this->dispatcher->trigger(self::PRE_FOO);

        $this->assertCount(1, $args);
        $this->assertInstanceOf('\Lemon\Event\Event', $args[0]);

        $this->dispatcher->trigger($event);
        $this->assertSame($event, $args[0]);
    }
}
