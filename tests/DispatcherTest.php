<?php

namespace Lemon\Event\Test;

use Lemon\Event\Event;
use Lemon\Event\Dispatcher;

class DispatcherTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Lemon\Event\Dispatcher
     */
    protected $dispatcher;

    /**
     * Sets up the fixture
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->dispatcher = new Dispatcher();
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
     * 
     */
    public function testInitialState()
    {
        $this->markTestIncomplete();
    }
}
