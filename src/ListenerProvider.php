<?php

declare(strict_types=1);

namespace FastD\Event;

use Psr\EventDispatcher\ListenerProviderInterface;
use SplPriorityQueue;

class ListenerProvider implements ListenerProviderInterface
{
    protected array $events = [];

    protected array $listeners = [];

    public function addListener(EventListenerInterface ...$eventListeners): void
    {
        foreach ($eventListeners as $eventListener) {
            $listenerClass = $eventListener::class;
            if (!isset($this->listeners[$listenerClass])) {
                $this->listeners[$listenerClass] = $eventListener;
                foreach ($eventListener->listen() as $event) {
                    $this->events[$event][] = $eventListener;
                }
            }
        }
    }

    public function getListenersForEvent(object $event): iterable
    {
        if (!isset($this->events[$event::class])) {
            return [];
        }

        $splPriorityQueue = new SplPriorityQueue();
        foreach ($this->events[$event::class] as $listener) {
            $splPriorityQueue->insert($listener, $listener->priority());
        }

        return $splPriorityQueue;
    }
}