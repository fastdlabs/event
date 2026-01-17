<?php

namespace Tests;

use FastD\Event\EventDispatcher;
use FastD\Event\ListenerProvider;
use FastD\Event\EventPriority;
use Tests\_support\TestEvent;
use Tests\_support\StoppableTestEvent;
use Tests\_support\BaseEvent;
use Tests\_support\ChildEvent;
use PHPUnit\Framework\TestCase;

class IntegrationTest extends TestCase
{
    public function testFullEventFlow(): void
    {
        $listenerProvider = new ListenerProvider();
        $eventDispatcher = new EventDispatcher($listenerProvider);

        $executionOrder = [];

        // Register listeners with different priorities
        $listenerProvider->addListener(
            TestEvent::class,
            function (TestEvent $event) use (&$executionOrder) {
                $executionOrder[] = 'first';
            },
            EventPriority::HIGH
        );

        $listenerProvider->addListener(
            TestEvent::class,
            function (TestEvent $event) use (&$executionOrder) {
                $executionOrder[] = 'second';
            },
            EventPriority::NORMAL
        );

        $listenerProvider->addListener(
            TestEvent::class,
            function (TestEvent $event) use (&$executionOrder) {
                $executionOrder[] = 'third';
            },
            EventPriority::LOW
        );

        $event = new TestEvent('integration_test');
        $result = $eventDispatcher->dispatch($event);

        $this->assertSame($event, $result);
        $this->assertEquals(['first', 'second', 'third'], $executionOrder);
    }

    public function testStoppableEventFlow(): void
    {
        $listenerProvider = new ListenerProvider();
        $eventDispatcher = new EventDispatcher($listenerProvider);

        $executionCount = 0;

        $listenerProvider->addListener(
            StoppableTestEvent::class,
            function (StoppableTestEvent $event) use (&$executionCount) {
                $executionCount++;
                $event->stopPropagation();
            }
        );

        $listenerProvider->addListener(
            StoppableTestEvent::class,
            function (StoppableTestEvent $event) use (&$executionCount) {
                $executionCount++; // This should not be executed
            }
        );

        $event = new StoppableTestEvent('stoppable_test');
        $eventDispatcher->dispatch($event);

        $this->assertEquals(1, $executionCount);
        $this->assertTrue($event->isPropagationStopped());
    }

    public function testEventInheritanceSupport(): void
    {
        $listenerProvider = new ListenerProvider();
        $eventDispatcher = new EventDispatcher($listenerProvider);

        $executionCount = 0;

        // Register listener for base event class
        $listenerProvider->addListener(
            BaseEvent::class,
            function (BaseEvent $event) use (&$executionCount) {
                $executionCount++;
            }
        );

        // Dispatch child event - should trigger base event listener
        $event = new ChildEvent('inheritance_test');
        $eventDispatcher->dispatch($event);

        $this->assertEquals(1, $executionCount);
    }
}