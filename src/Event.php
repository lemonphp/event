<?php

namespace Lemon\Event;

class Event
{
    /**
     * @var string Read only property
     */
    protected $name;

    /**
     * @var bool Whether no further event listeners should be triggered
     */
    protected $stopped = false;

    /**
     * @param string $name
     */
    public function __construct($name)
    {
        $this->name = (string) $name;
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
    public function getName()
    {
        return $this->name;
    }
}
