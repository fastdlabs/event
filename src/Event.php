<?php

declare(strict_types=1);

namespace FastD\Event;

use Psr\EventDispatcher\StoppableEventInterface;
use Throwable;

class Event implements StoppableEventInterface, ThrowableEventInterface
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

    public function addThrowable(EventListenerInterface $eventListener, Throwable $throwable): void
    {
        $this->exceptions[get_class($eventListener)] = $throwable;
    }

    public function getThrowables(): array
    {
        return $this->exceptions;
    }
}