<?php

/*
 * This file is part of `lemonphp/event` project.
 *
 * (c) 2015-2016 LemonPHP Team
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Lemon\Event\Tests\Stub;

use Lemon\Event\EventSubscriberInterface;

class EventSubscriber implements EventSubscriberInterface
{
    public $preFooInvoked  = false;
    public $postFooInvoked = false;
    public $preBarInvoked  = false;
    public $postBarInvoked = false;

    public function getSubscribedEvents()
    {
        return [
            'pre.foo'  => 'preFoo',
            'post.foo' => ['postFoo', 10],
            'bar'      => [['preBar', 10], ['postBar']],
        ];
    }

    public function preFoo(Event $e)
    {
        $this->preFooInvoked = true;
    }

    public function postFoo(Event $e)
    {
        $this->postFooInvoked = true;
    }

    public function preBar(Event $e)
    {
        $this->preBarInvoked = true;
    }

    public function postBar(Event $e)
    {
        $this->postBarInvoked = true;
    }
}
