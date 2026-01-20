<?php

declare(strict_types=1);

namespace Tests;

use FastD\Event\Event;

class TestEvent extends Event
{
    public function __construct(private string $name = 'test')
    {
    }

    public function getName(): string
    {
        return $this->name;
    }
}