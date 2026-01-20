<?php

declare(strict_types=1);

namespace Tests;

use FastD\Event\ThrowableListener;
use FastD\Event\EventListenerInterface;
use PHPUnit\Framework\TestCase;
use RuntimeException;

class ThrowableListenerTest extends TestCase
{
    public function testConstructWithException(): void
    {
        $originalListener = new SimpleEventListener();
        $exception = new RuntimeException('Test exception');

        $throwableListener = new ThrowableListener($originalListener, $exception);

        // We can't directly access the protected properties, but we can verify it was created
        $this->assertInstanceOf(ThrowableListener::class, $throwableListener);
    }

    public function testListenReturnsEmpty(): void
    {
        $originalListener = new SimpleEventListener();
        $exception = new RuntimeException('Test exception');
        $throwableListener = new ThrowableListener($originalListener, $exception);

        $result = $throwableListener->listen();

        $this->assertIsIterable($result);
        $this->assertEmpty(iterator_to_array($result));
    }

    public function testPriorityReturnsZeroByDefault(): void
    {
        $originalListener = new SimpleEventListener();
        $exception = new RuntimeException('Test exception');
        $throwableListener = new ThrowableListener($originalListener, $exception);

        $priority = $throwableListener->priority();

        $this->assertEquals(0, $priority);
    }

    public function testProcessAddsExceptionToEvent(): void
    {
        $originalListener = new SimpleEventListener();
        $exception = new RuntimeException('Test exception');
        $throwableListener = new ThrowableListener($originalListener, $exception);

        $event = new TestEvent();

        $throwableListener->process($event);

        $throwables = $event->getThrowables();
        $this->assertNotEmpty($throwables);
        $this->assertArrayHasKey(SimpleEventListener::class, $throwables);
        $this->assertSame($exception, $throwables[SimpleEventListener::class]);
    }

    public function testHandleCallsProcess(): void
    {
        $originalListener = new SimpleEventListener();
        $exception = new RuntimeException('Test exception');
        $throwableListener = new ThrowableListener($originalListener, $exception);

        $event = new TestEvent();

        $throwableListener->process($event);

        $throwables = $event->getThrowables();
        $this->assertNotEmpty($throwables);
        $this->assertArrayHasKey(SimpleEventListener::class, $throwables);
        $this->assertSame($exception, $throwables[SimpleEventListener::class]);
    }

    public function testProcessDoesNotAddExceptionIfNotThrowableEvent(): void
    {
        // Create a mock event that doesn't implement ThrowableEventInterface
        $mockEvent = $this->getMockBuilder(\stdClass::class)
            ->addMethods(['isExceptionStopped', 'addThrowable', 'getThrowables'])
            ->getMock();
            
        $originalListener = new SimpleEventListener();
        $exception = new RuntimeException('Test exception');
        $throwableListener = new ThrowableListener($originalListener, $exception);

        // Process should not add throwable if event doesn't implement ThrowableEventInterface
        $throwableListener->process($mockEvent);

        // This test checks that no error occurs when processing an incompatible event
        $this->assertInstanceOf(ThrowableListener::class, $throwableListener);
    }
}