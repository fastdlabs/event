<?php

declare(strict_types=1);

namespace FastD\Event;

abstract class EventListener implements EventListenerInterface
{
    public function priority(): int
    {
        return 0;
    }

    abstract public function process(object $event): void;
}