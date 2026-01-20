<?php

declare(strict_types=1);

namespace Tests;

use FastD\Event\EventListener;

class PriorityEventListener extends EventListener
{
    public function __construct(private int $priorityValue = 0)
    {
    }

    public function listen(): iterable
    {
        return [TestEvent::class];
    }

    public function process(object $event): void
    {
        // Do nothing
    }

    public function handle(object $event): void
    {
        $this->process($event);
    }

    public function priority(): int
    {
        return $this->priorityValue;
    }
}