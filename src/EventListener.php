<?php

declare(strict_types=1);

namespace FastD\Event;

use FastD\Event\Contract\EventListenerInterface;

abstract class EventLListener implements EventListenerInterface
{

    public function listen(): iterable
    {
        // TODO: Implement listen() method.
    }

    public function process(object $event): void
    {
        // TODO: Implement process() method.
    }

    public function getPriority(): int
    {
        // TODO: Implement getPriority() method.
    }
}