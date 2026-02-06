<?php

declare(strict_types=1);

namespace FastD\Event;

use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\EventDispatcher\ListenerProviderInterface;
use Psr\EventDispatcher\StoppableEventInterface;
use Throwable;

class EventDispatcher implements EventDispatcherInterface
{
    public function __construct(
        public readonly ListenerProviderInterface $listenerProvider
    ) {}

    public function dispatch(object $event): object
    {
        if (
            ($event instanceof StoppableEventInterface && $event->isPropagationStopped())
            || ($event instanceof ThrowableEventInterface && $event->isExceptionStopped())
        ) {
            return $event;
        }

        $listeners = $this->listenerProvider->getListenersForEvent($event);
        
        foreach ($listeners as $listener) {
            try {
                $listener->process($event);
            } catch (Throwable $throwable) {
                (new ThrowableListener($listener, $throwable))->process($event);
            }

            if (
                ($event instanceof StoppableEventInterface && $event->isPropagationStopped())
                || ($event instanceof ThrowableEventInterface && $event->isExceptionStopped())
            ) {
                break;
            }
        }

        return $event;
    }

    public function addListener(EventListenerInterface ...$eventListener): void
    {
        $this->listenerProvider->addListener(...$eventListener);
    }
}