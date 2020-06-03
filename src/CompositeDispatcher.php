<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2020
 *
 * @see      https://www.github.com/fastdlabs
 * @see      http://www.fastdlabs.com/
 */

namespace FastD\Event;


use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\EventDispatcher\StoppableEventInterface;

class CompositeDispatcher implements EventDispatcherInterface
{
    /**
     * @var EventDispatcherInterface[]
     */
    private array $dispatchers = [];

    public function dispatch(object $event)
    {
        foreach ($this->dispatchers as $dispatcher) {
            if ($event instanceof StoppableEventInterface && $event->isPropagationStopped()) {
                return $event;
            }
            $event = $dispatcher->dispatch($event);
        }

        return $event;
    }

    public function attach(EventDispatcherInterface $dispatcher): void
    {
        $this->dispatchers[] = $dispatcher;
    }
}