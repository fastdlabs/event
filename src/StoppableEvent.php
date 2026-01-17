<?php

declare(strict_types=1);

namespace FastD\Event;

use Psr\EventDispatcher\StoppableEventInterface;

class StoppableEvent implements StoppableEventInterface
{
    protected bool $propagationStopped = false;

    public function isPropagationStopped(): bool
    {
        return $this->propagationStopped;
    }

    public function stopPropagation(): void
    {
        $this->propagationStopped = true;
    }
}