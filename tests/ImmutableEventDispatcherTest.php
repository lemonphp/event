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
use Lemon\Event\ImmutableEventDispatcher;
use Lemon\Event\Tests\Stub\EventListener;
use Lemon\Event\Tests\Stub\EventSubscriber;

class ImmutableEventDispatcherTest extends \PHPUnit_Framework_TestCase
{
    /* Some pseudo events */
    const PRE_FOO  = 'pre.foo';
    const POST_FOO = 'post.foo';
    const PRE_BAR  = 'pre.bar';
    const POST_BAR = 'post.bar';

    /**
     * @var \Lemon\Event\ImmutableEventDispatcher
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
        $this->listener = new EventListener();

        $dispatcher = new EventDispatcher();
        $dispatcher->addListener(self::POST_FOO, [$this->listener, 'postFoo']);

        $this->dispatcher = new ImmutableEventDispatcher($dispatcher);
    }

    /**
     * Tears down the fixture
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
        unset($this->dispatcher, $this->listener);
    }

    /**
     * Test constructor
     */
    public function testContructor()
    {
        $dispatcher = new EventDispatcher();
        $this->dispatcher = new ImmutableEventDispatcher($dispatcher);

        $this->assertSame($dispatcher, $this->getObjectAttribute($this->dispatcher, 'dispatcher'));

        return $this->dispatcher;
    }

    /**
     * Test initinal state of dispatcher
     *
     * @param ImmutableEventDispatcher $dispatcher
     * @depends testContructor
     */
    public function testInitialState($dispatcher)
    {
        $this->assertSame([], $dispatcher->getListeners());
        $this->assertFalse($dispatcher->hasListeners(self::PRE_FOO));
        $this->assertFalse($dispatcher->hasListeners(self::POST_FOO));
    }

    /**
     * Test dispatch
     */
    public function testDispatch()
    {
        $dispatcher = $this->getMockBuilder('Lemon\Event\EventDispatcher')
            ->setMethods(['dispatch'])
            ->getMock();

        $dispatcher->expects($this->once())
            ->method('dispatch')
            ->with($this->equalTo(self::POST_FOO));

        $this->dispatcher = new ImmutableEventDispatcher($dispatcher);
        $this->dispatcher->dispatch(self::POST_FOO);
    }

    /**
     * Test add listener
     *
     * @expectedException \BadMethodCallException
     * @expectedExceptionMessage Unmodifiable event dispatchers must not be modified.
     */
    public function testAddListener()
    {
        $this->dispatcher->addListener(self::PRE_FOO, [$this->listener, 'preFoo']);
    }

    /**
     * Test add listener with priority
     *
     * @expectedException \BadMethodCallException
     * @expectedExceptionMessage Unmodifiable event dispatchers must not be modified.
     */
    public function testAddListenerWithPriority()
    {
        $this->dispatcher->addListener(self::PRE_FOO, [$this->listener, 'preFoo'], 10);
    }

    /**
     * Test remove listener
     *
     * @expectedException \BadMethodCallException
     * @expectedExceptionMessage Unmodifiable event dispatchers must not be modified.
     */
    public function testRemoveListener()
    {
        $this->dispatcher->removeListener(self::POST_FOO, [$this->listener, 'postFoo']);
    }

    /**
     * Test remove all listeners
     *
     * @expectedException \BadMethodCallException
     * @expectedExceptionMessage Unmodifiable event dispatchers must not be modified.
     */
    public function testRemoveAllListeners()
    {
        $this->dispatcher->removeAllListeners(self::POST_FOO);
    }

    /**
     * Test remove all listeners without event type
     *
     * @expectedException \BadMethodCallException
     * @expectedExceptionMessage Unmodifiable event dispatchers must not be modified.
     */
    public function testRemoveAllListenersWithoutEventType()
    {
        $this->dispatcher->removeAllListeners();
    }

    /**
     * Test get listeners
     */
    public function testGetListeners()
    {
        $dispatcher = $this->getMockBuilder('Lemon\Event\EventDispatcher')
            ->setMethods(['getListeners'])
            ->getMock();

        $dispatcher->expects($this->once())
            ->method('getListeners')
            ->with($this->equalTo(self::POST_FOO))
            ->willReturn([[$this->listener, 'postFoo']]);

        $this->dispatcher = new ImmutableEventDispatcher($dispatcher);

        $this->assertSame([[$this->listener, 'postFoo']], $this->dispatcher->getListeners(self::POST_FOO));
    }

    /**
     * Test has listeners
     */
    public function testHasListeners()
    {
        $dispatcher = $this->getMockBuilder('Lemon\Event\EventDispatcher')
            ->setMethods(['hasListeners'])
            ->getMock();

        $dispatcher->expects($this->once())
            ->method('hasListeners')
            ->with($this->equalTo(self::POST_FOO))
            ->willReturn(true);

        $this->dispatcher = new ImmutableEventDispatcher($dispatcher);

        $this->assertTrue($this->dispatcher->hasListeners(self::POST_FOO));
    }

    /**
     * Test add subscriber
     *
     * @expectedException \BadMethodCallException
     * @expectedExceptionMessage Unmodifiable event dispatchers must not be modified.
     */
    public function testAddSubscriber()
    {
        $subscriber = new EventSubscriber();

        $this->dispatcher->addSubscriber($subscriber);
    }

    /**
     * Test remove subscriber
     *
     * @expectedException \BadMethodCallException
     * @expectedExceptionMessage Unmodifiable event dispatchers must not be modified.
     */
    public function testRemoveSubscriber()
    {
        $subscriber = new EventSubscriber();

        $this->dispatcher->removeSubscriber($subscriber);
    }
}
