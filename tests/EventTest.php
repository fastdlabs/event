<?php

declare(strict_types=1);

namespace Tests;

use FastD\Event\Event;
use PHPUnit\Framework\Attributes\AllowMockObjectsWithoutExpectations;
use PHPUnit\Framework\TestCase;
use Tests\SimpleEventListener;
use RuntimeException;

class EventTest extends TestCase
{
    public function testEventInitializesCorrectly(): void
    {
        $event = new Event();

        $this->assertFalse($event->isPropagationStopped());
        $this->assertFalse($event->isExceptionStopped());
        $this->assertEmpty($event->getThrowables());
    }

    public function testStopPropagation(): void
    {
        $event = new Event();

        $this->assertFalse($event->isPropagationStopped());

        $event->stopPropagation();

        $this->assertTrue($event->isPropagationStopped());
    }

    public function testStopException(): void
    {
        $event = new Event();

        $this->assertFalse($event->isExceptionStopped());

        $event->stopException();

        $this->assertTrue($event->isExceptionStopped());
    }

    public function testAddAndGetThrowables(): void
    {
        $event = new Event();
        $listener = new SimpleEventListener();
        $exception = new RuntimeException('Test exception');

        $this->assertEmpty($event->getThrowables());

        $event->addThrowable($listener, $exception);

        $throwables = $event->getThrowables();
        $this->assertCount(1, $throwables);
        $this->assertArrayHasKey(SimpleEventListener::class, $throwables);
        $this->assertSame($exception, $throwables[SimpleEventListener::class]);
    }

    #[AllowMockObjectsWithoutExpectations]
    public function testMultipleExceptions(): void
    {
        $event = new Event();
        $listener1 = new SimpleEventListener();
        
        // Create another listener class to differentiate
        $differentListener = $this->getMockBuilder(SimpleEventListener::class)
            ->onlyMethods([])
            ->getMock();
        $exception1 = new RuntimeException('Test exception 1');
        $exception2 = new RuntimeException('Test exception 2');

        $event->addThrowable($listener1, $exception1);
        $event->addThrowable($differentListener, $exception2);

        $throwables = $event->getThrowables();
        $this->assertCount(2, $throwables);
        $this->assertArrayHasKey(SimpleEventListener::class, $throwables);
        $this->assertArrayHasKey(get_class($differentListener), $throwables);
        $this->assertSame($exception1, $throwables[SimpleEventListener::class]);
        $this->assertSame($exception2, $throwables[get_class($differentListener)]);
    }
}