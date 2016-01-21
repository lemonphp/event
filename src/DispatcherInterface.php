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

interface DispatcherInterface
{

    /**
     * Dispatches an event to all registered listeners.
     *
     * @param Event|string  $event The event or event name to pass to the event handlers/listeners.
     * @return Event
     */
    public function trigger($event);

    /**
     * Adds an event listener that listens on the specified events.
     *
     * @param string   $eventName The event to listen on
     * @param callable $listener  The listener. It passed Event object is first argument
     * @param int      $priority  The higher this value, the earlier an event
     *                            listener will be triggered in the chain (defaults to 0)
     */
    public function on($eventName, $listener, $priority = 0);

    /**
     * Removes an event listener from the specified events.
     *
     * @param string   $eventName The event to remove a listener from
     * @param callable $listener  The listener to remove
     */
    public function off($eventName, $listener = null);

    /**
     * Gets the listeners of a specific event or all listeners sorted by descending priority.
     *
     * @param string $eventName The name of the event
     *
     * @return array The event listeners for the specified event, or all event listeners by event name
     */
    public function getListeners($eventName = null);

    /**
     * Checks whether an event has any registered listeners.
     *
     * @param string $eventName The name of the event
     *
     * @return bool true if the specified event has any listeners, false otherwise
     */
    public function hasListeners($eventName = null);
}
