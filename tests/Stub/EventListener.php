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

use Lemon\Event\Event;

class EventListener
{
    public $preFooInvoked  = false;
    public $postFooInvoked = false;

    public function preFoo(Event $e)
    {
        $this->preFooInvoked = true;
    }

    public function postFoo(Event $e)
    {
        $this->postFooInvoked = true;
        $e->stopPropagation();
    }
}
