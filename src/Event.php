<?php

/*
 * This file is part of `lemon/event` project.
 *
 * (c) 2015-2016 LemonPHP Team
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Lemon\Event;

class Event
{
    /**
     * @var string Read only property
     */
    protected $eventName;

    /**
     * @var bool Whether no further event listeners should be triggered
     */
    protected $stopped = false;

    /**
     * @param string $name
     */
    public function __construct($name)
    {
        $this->eventName = (string) $name;
    }

    /**
     * Returns whether further event listeners should be triggered.
     *
     * @see Event::stopPropagation()
     * @return bool Whether propagation was already stopped for this event.
     */
    public function isPropagationStopped()
    {
        return $this->stopped;
    }

    /**
     * Stops the propagation of the event to further event listeners.
     *
     * If multiple event listeners are connected to the same event, no
     * further event listener will be triggered once any trigger calls
     * stopPropagation().
     */
    public function stopPropagation()
    {
        $this->stopped = true;
    }

    /**
     * @return string
     */
    public function getEventName()
    {
        return $this->eventName;
    }
}
