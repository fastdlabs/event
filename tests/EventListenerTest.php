<?php

declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;

class EventListenerTest extends TestCase
{
    public function testPriorityReturnsZeroByDefault(): void
    {
        $listener = new SimpleEventListener();

        $priority = $listener->priority();

        $this->assertEquals(0, $priority);
    }

    public function testListenMethodReturnsIterable(): void
    {
        $listener = new SimpleEventListener();

        $result = $listener->listen();

        $this->assertIsIterable($result);
        $this->assertContains(TestEvent::class, iterator_to_array($result));
    }

    public function testProcessMethodImplementation(): void
    {
        $listener = new SimpleEventListener();

        $event = new TestEvent();

        // Clear log before test
        SimpleEventListener::clearProcessLog();

        $listener->process($event);

        $this->assertEquals(['processed'], SimpleEventListener::getProcessLog());
    }

    public function testHandleMethodCallsProcess(): void
    {
        $listener = new SimpleEventListener();

        $event = new TestEvent();

        // Clear log before test
        SimpleEventListener::clearProcessLog();

        $listener->handle($event);

        $this->assertEquals(['processed'], SimpleEventListener::getProcessLog());
    }
}