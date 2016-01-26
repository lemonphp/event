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
use Lemon\Event\EventDispatcher;
use Lemon\Event\Tests\Stub\EventListener;
use Lemon\Event\Tests\Stub\EventSubscriber;

class EventDispatcherTest extends \PHPUnit_Framework_TestCase
{
    /* Some pseudo events */
    const PRE_FOO  = 'pre.foo';
    const POST_FOO = 'post.foo';
    const PRE_BAR  = 'pre.bar';
    const POST_BAR = 'post.bar';

    /**
     * @var \Lemon\Event\EventDispatcher
     */
    protected $dispatcher;

    /**
     * @var \Lemon\Event\Tests\Stub\EventListener
     */
    protected $listener;

    /**
     * Sets up the fixture
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->dispatcher = new EventDispatcher();
        $this->listener   = new EventListener();
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

    /**
     * Test add listener
     */
    public function testAddListener()
    {
        $this->dispatcher->addListener(self::PRE_FOO, [$this->listener, 'preFoo']);
        $this->dispatcher->addListener(self::POST_FOO, [$this->listener, 'postFoo']);

        $this->assertTrue($this->dispatcher->hasListeners(self::PRE_FOO));
        $this->assertTrue($this->dispatcher->hasListeners(self::POST_FOO));
        $this->assertFalse($this->dispatcher->hasListeners(self::PRE_BAR));
        $this->assertCount(1, $this->dispatcher->getListeners(self::PRE_FOO));
        $this->assertCount(1, $this->dispatcher->getListeners(self::POST_FOO));
        $this->assertCount(2, $this->dispatcher->getListeners());
    }

    /**
     * Test listener with priority
     */
    public function testAddListenerWithPriority()
    {
        $this->dispatcher->addListener(self::PRE_FOO, [$this->listener, 'preFoo'], 10);

        $this->assertTrue($this->dispatcher->hasListeners(self::PRE_FOO));
        $this->assertCount(1, $this->dispatcher->getListeners(self::PRE_FOO));
        $this->assertSame(10, $this->dispatcher->getListenerPriority(self::PRE_FOO, [$this->listener, 'preFoo']));
    }

    public function testRemoveListener()
    {
        $listener1 = new EventListener();

        $this->dispatcher->addListener(self::PRE_FOO, [$listener1, 'preFoo']);
        $this->dispatcher->addListener(self::PRE_FOO, [$this->listener, 'preFoo']);
        $this->assertCount(2, $this->dispatcher->getListeners(self::PRE_FOO));

        $this->dispatcher->removeListener(self::PRE_FOO, [$this->listener, 'preFoo']);
        $this->assertCount(1, $this->dispatcher->getListeners(self::PRE_FOO));
        $this->assertNull($this->dispatcher->getListenerPriority(self::PRE_FOO, [$this->listener, 'preFoo']));

        $this->dispatcher->removeListener('noevent', [$listener1, 'preFoo']);
        $this->assertCount(1, $this->dispatcher->getListeners(self::PRE_FOO));

        $this->dispatcher->removeListener(self::PRE_FOO, [$listener1, 'preBar']);
        $this->assertCount(1, $this->dispatcher->getListeners(self::PRE_FOO));
    }

    public function testRemoveAllListeners()
    {
        $listener1 = new EventListener();
        $listener2 = new EventListener();

        $this->dispatcher->addListener(self::PRE_FOO, [$listener1, 'preFoo']);
        $this->dispatcher->addListener(self::PRE_FOO, [$listener2, 'preFoo']);
        $this->dispatcher->addListener(self::PRE_BAR, [$this->listener, 'preBar']);
        $this->assertCount(2, $this->dispatcher->getListeners(self::PRE_FOO));
        $this->assertCount(1, $this->dispatcher->getListeners(self::PRE_BAR));

        $this->dispatcher->removeAllListeners(self::PRE_FOO);
        $this->assertFalse($this->dispatcher->hasListeners(self::PRE_FOO));
        $this->assertCount(1, $this->dispatcher->getListeners(self::PRE_BAR));
    }

    public function testRemoveAllListenersWithoutEventType()
    {
        $listener1 = new EventListener();
        $listener2 = new EventListener();

        $this->dispatcher->addListener(self::PRE_FOO, [$listener1, 'preFoo']);
        $this->dispatcher->addListener(self::PRE_FOO, [$listener2, 'preFoo']);
        $this->dispatcher->addListener(self::PRE_BAR, [$this->listener, 'preBar']);
        $this->assertCount(2, $this->dispatcher->getListeners(self::PRE_FOO));
        $this->assertCount(1, $this->dispatcher->getListeners(self::PRE_BAR));

        $this->dispatcher->removeAllListeners();
        $this->assertCount(0, $this->dispatcher->getListeners());
    }

    public function testGetListenersSortByPriority()
    {
        $listener1 = new EventListener();
        $listener2 = new EventListener();
        $listener3 = new EventListener();
        $this->dispatcher->addListener(self::PRE_FOO, [$listener1, 'preFoo'], -10);
        $this->dispatcher->addListener(self::PRE_FOO, [$listener2, 'preFoo'], 10);
        $this->dispatcher->addListener(self::PRE_FOO, [$listener3, 'preFoo']);

        $expected = [
            [$listener2, 'preFoo'],
            [$listener3, 'preFoo'],
            [$listener1, 'preFoo'],
        ];

        $this->assertSame($expected, $this->dispatcher->getListeners(self::PRE_FOO));
    }

    public function testGetAllListenersSortByPriority()
    {
        $listener1 = new EventListener();
        $listener2 = new EventListener();
        $listener3 = new EventListener();
        $this->dispatcher->addListener(self::PRE_FOO, [$listener1, 'preFoo'], -10);
        $this->dispatcher->addListener(self::PRE_FOO, [$listener2, 'preFoo']);
        $this->dispatcher->addListener(self::PRE_FOO, [$listener3, 'preFoo'], 10);
        $this->dispatcher->addListener(self::POST_FOO, [$listener1, 'postFoo'], -10);
        $this->dispatcher->addListener(self::POST_FOO, [$listener2, 'postFoo']);
        $this->dispatcher->addListener(self::POST_FOO, [$listener3, 'postFoo'], 10);

        $expected = [
            'pre.foo'  => [[$listener3, 'preFoo'], [$listener2, 'preFoo'], [$listener1, 'preFoo']],
            'post.foo' => [[$listener3, 'postFoo'], [$listener2, 'postFoo'], [$listener1, 'postFoo']],
        ];

        $this->assertSame($expected, $this->dispatcher->getListeners());
    }

    public function testGetListenerPriority()
    {
        $listener1 = new EventListener();
        $listener2 = new EventListener();
        $this->dispatcher->addListener(self::PRE_FOO, [$listener1, 'preFoo'], -10);
        $this->dispatcher->addListener(self::PRE_FOO, [$listener2, 'preFoo']);

        $this->assertSame(-10, $this->dispatcher->getListenerPriority(self::PRE_FOO, [$listener1, 'preFoo']));
        $this->assertSame(0, $this->dispatcher->getListenerPriority(self::PRE_FOO, [$listener2, 'preFoo']));
        $this->assertNull($this->dispatcher->getListenerPriority(self::PRE_BAR, [$listener1, 'preFoo']));
        $this->assertNull($this->dispatcher->getListenerPriority(self::PRE_FOO, function() {}));
    }

    public function testDispatch()
    {
        $this->dispatcher->addListener(self::PRE_FOO, [$this->listener, 'preFoo']);
        $this->dispatcher->addListener(self::POST_FOO, [$this->listener, 'postFoo']);
        $this->dispatcher->dispatch(self::PRE_FOO);
        $event = new Event('test.event');

        $this->assertTrue($this->listener->preFooInvoked);
        $this->assertFalse($this->listener->postFooInvoked);
        $this->assertInstanceOf('\Lemon\Event\Event', $this->dispatcher->dispatch('noevent'));
        $this->assertInstanceOf('\Lemon\Event\Event', $this->dispatcher->dispatch(self::PRE_FOO));
        $this->assertSame($event, $this->dispatcher->dispatch($event));
    }

    public function testDispatchForClosure()
    {
        $invoked  = '|';
        $listener = function (Event $e) use (&$invoked) {
            $invoked .= ($e->getEventType() . '|');
        };

        $this->dispatcher->addListener(self::PRE_FOO, $listener);
        $this->dispatcher->addListener(self::POST_FOO, $listener);
        $this->dispatcher->dispatch(self::PRE_FOO);

        $this->assertSame('|pre.foo|', $invoked);
    }

    public function testDispatchOrderByPriority()
    {
        $invoked   = [];
        $listener1 = function() use (&$invoked) {
            $invoked[] = 1;
        };
        $listener2 = function() use (&$invoked) {
            $invoked[] = 2;
        };
        $listener3 = function() use (&$invoked) {
            $invoked[] = 3;
        };

        $this->dispatcher->addListener(self::PRE_FOO, $listener1, -10);
        $this->dispatcher->addListener(self::PRE_FOO, $listener2);
        $this->dispatcher->addListener(self::PRE_FOO, $listener3, 10);
        $this->dispatcher->dispatch(self::PRE_FOO);

        $this->assertSame([3, 2, 1], $invoked);
    }

    public function testStopEventPropagation()
    {
        $otherListener = new EventListener();

        $this->dispatcher->addListener(self::POST_FOO, [$this->listener, 'postFoo'], 10);
        $this->dispatcher->addListener(self::POST_FOO, [$otherListener, 'postFoo']);
        $this->dispatcher->dispatch(self::POST_FOO);

        $this->assertTrue($this->listener->postFooInvoked);
        $this->assertFalse($otherListener->postFooInvoked);
    }

    public function testListenerArgument()
    {
        $args     = [];
        $listener = function() use (&$args) {
            $args = func_get_args();
        };
        $event = new Event(self::PRE_FOO);

        $this->dispatcher->addListener(self::PRE_FOO, $listener);
        $this->dispatcher->dispatch(self::PRE_FOO);

        $this->assertCount(1, $args);
        $this->assertInstanceOf('\Lemon\Event\Event', $args[0]);

        $this->dispatcher->dispatch($event);
        $this->assertSame($event, $args[0]);
    }

    public function testAddSubscriber()
    {
        $subscriber = new EventSubscriber();

        $this->dispatcher->addListener('pre.foo', [$this->listener, 'preFoo']);
        $this->dispatcher->addSubscriber($subscriber);

        $this->assertCount(2, $this->dispatcher->getListeners('pre.foo'));
        $this->assertCount(1, $this->dispatcher->getListeners('post.foo'));
        $this->assertCount(2, $this->dispatcher->getListeners('bar'));
        $this->assertSame(0, $this->dispatcher->getListenerPriority('pre.foo', [$subscriber, 'preFoo']));
        $this->assertSame(10, $this->dispatcher->getListenerPriority('post.foo', [$subscriber, 'postFoo']));
        $this->assertSame(10, $this->dispatcher->getListenerPriority('bar', [$subscriber, 'preBar']));
        $this->assertSame(0, $this->dispatcher->getListenerPriority('bar', [$subscriber, 'postBar']));
    }

    public function testRemoveSubscriber()
    {
        $subscriber = new EventSubscriber();

        $this->dispatcher->addListener('pre.foo', [$this->listener, 'preFoo'], -10);
        $this->dispatcher->addSubscriber($subscriber);
        $this->dispatcher->removeSubscriber($subscriber);

        $this->assertCount(1, $this->dispatcher->getListeners('pre.foo'));
        $this->assertCount(0, $this->dispatcher->getListeners('post.foo'));
        $this->assertCount(0, $this->dispatcher->getListeners('bar'));
        $this->assertNull($this->dispatcher->getListenerPriority('pre.foo', [$subscriber, 'preFoo']));
        $this->assertNull($this->dispatcher->getListenerPriority('post.foo', [$subscriber, 'postFoo']));
        $this->assertNull($this->dispatcher->getListenerPriority('bar', [$subscriber, 'preBar']));
        $this->assertNull($this->dispatcher->getListenerPriority('bar', [$subscriber, 'postBar']));

        $this->assertSame(-10, $this->dispatcher->getListenerPriority('pre.foo', [$this->listener, 'preFoo']));
    }
}
