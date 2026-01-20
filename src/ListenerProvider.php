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
        if (empty($eventListeners)) {
            return;
        }
        foreach ($eventListeners as $eventListener) {
            $listenerClass = $eventListener::class;
            if (! isset($this->listeners[$listenerClass])) {
                $this->listeners[$listenerClass] = $eventListener;
                foreach ($eventListener->listen() as $event) {
                    $this->events[$event][] = $eventListener;
                }
            }
        }
    }

    public function getListenersForEvent(object $event): iterable
    {
        $listeners = $this->events[$event::class] ?? [];
        $splPriorityQueue = new SplPriorityQueue();
        foreach ($listeners as $listener) {
            $splPriorityQueue->insert($listener, $listener->priority());
        }

        return $splPriorityQueue;
    }
}