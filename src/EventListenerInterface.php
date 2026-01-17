<?php

declare(strict_types=1);

namespace FastD\Event;

use Psr\EventDispatcher\StoppableEventInterface;

interface EventListenerInterface
{
    public function __invoke(StoppableEventInterface $stoppableEvent): void;
}