<?php

declare(strict_types=1);

namespace Tests;

use FastD\Event\EventListener;
use RuntimeException;

class ThrowingEventListener extends EventListener
{
    public function listen(): iterable
    {
        return [TestEvent::class];
    }

    public function process(object $event): void
    {
        throw new RuntimeException('Test exception');
    }

    public function handle(object $event): void
    {
        $this->process($event);
    }
}