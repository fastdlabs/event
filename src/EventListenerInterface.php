<?php

declare(strict_types=1);

namespace FastD\Event\Contract;

interface EventListenerInterface
{
    public function listen(): iterable;

    public function process(object $event): void;

    public function priority(): int;
}