<?php

namespace FastD\Event;

use Throwable;

interface ThrowableEventInterface
{
    public function isExceptionStopped(): bool;

    public function addThrowable(EventListenerInterface $eventListener, Throwable $throwable): void;

    public function getThrowables(): array;
}