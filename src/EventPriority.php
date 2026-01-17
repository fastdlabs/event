<?php

declare(strict_types=1);

namespace FastD\Event;

final class EventPriority
{
    public const LOWEST = -1000;

    public const LOW = -100;

    public const NORMAL = 0;

    public const HIGH = 100;

    public const HIGHEST = 1000;
}