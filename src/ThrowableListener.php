<?php

declare(strict_types=1);

namespace FastD\Event;

use Throwable;

class ThrowableListener extends EventListener
{
    public function __construct(protected EventListenerInterface $eventListener, protected Throwable $throwable)
    {
    }

    public function listen(): iterable
    {
        return [];
    }

    public function process(object $event): void
    {
        if ($event instanceof ThrowableEventInterface) {
            $event->addThrowable($this->eventListener, $this->throwable);
        }
    }
}