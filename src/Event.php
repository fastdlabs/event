<?php

declare(strict_types=1);

namespace FastD\Event;

use Psr\EventDispatcher\StoppableEventInterface;
use Throwable;

class StoppableEvent implements StoppableEventInterface
{
    protected bool $propagationStopped = false;

    protected bool $exceptionStopped = false;

    protected array $exceptions = [];

    public function isPropagationStopped(): bool
    {
        return $this->propagationStopped;
    }

    public function stopPropagation(): void
    {
        $this->propagationStopped = true;
    }

    public function isExceptionStopped(): bool
    {
        return $this->exceptionStopped;
    }

    public function stopException(): void
    {
        $this->exceptionStopped = true;
    }

    public function addException(EventListenerInterface $listener, Throwable $exception): void
    {
        $this->exceptions[get_class($listener)] = $exception;
    }

    public function getExceptions(): array
    {
        return $this->exceptions;
    }
}