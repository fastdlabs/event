<?php

declare(strict_types=1);

namespace Tests;

use FastD\Event\EventListener;

class SimpleEventListener extends EventListener
{
    private static array $processLog = [];

    public function listen(): iterable
    {
        return [TestEvent::class];
    }

    public function process(object $event): void
    {
        self::$processLog[] = 'processed';
    }

    public function handle(object $event): void
    {
        $this->process($event);
    }

    public static function getProcessLog(): array
    {
        return self::$processLog;
    }

    public static function clearProcessLog(): void
    {
        self::$processLog = [];
    }
}