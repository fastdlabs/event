<?php

namespace Tests;

use FastD\Event\EventListenerInterface;
use FastD\Event\EventDispatcher;
use FastD\Event\ListenerProvider;
use FastD\Event\StoppableEvent;
use Tests\_support\TestEvent;
use Tests\_support\StoppableTestEvent;
use PHPUnit\Framework\TestCase;

// Create a test listener class that implements the interface
class TestEventListener implements EventListenerInterface
{
    private array $handledEvents = [];

    public function __invoke(object $event): void
    {
        $this->handle($event);
    }

    public function handle(object $event): void
    {
        $this->handledEvents[] = $event;
    }

    public function getHandledEvents(): array
    {
        return $this->handledEvents;
    }

    public function getHandledEventCount(): int
    {
        return count($this->handledEvents);
    }
}

class EventListenerInterfaceTest extends TestCase
{
    public function testEventListenerInterfaceCanBeImplemented(): void
    {
        $listener = new TestEventListener();

        // Should implement the interface
        $this->assertInstanceOf(EventListenerInterface::class, $listener);
    }

    public function testEventListenerCanHandleEvents(): void
    {
        $listener = new TestEventListener();
        
        $event1 = new TestEvent('test_data_1');
        $event2 = new TestEvent('test_data_2');

        $listener->handle($event1);
        $listener->handle($event2);

        $this->assertCount(2, $listener->getHandledEvents());
        $this->assertSame($event1, $listener->getHandledEvents()[0]);
        $this->assertSame($event2, $listener->getHandledEvents()[1]);
    }

    public function testEventListenerUsedWithEventDispatcher(): void
    {
        $provider = new ListenerProvider();
        $dispatcher = new EventDispatcher($provider);

        $listener = new TestEventListener();

        // Add listener for TestEvent
        $provider->addListener(TestEvent::class, $listener);

        $event = new TestEvent('test_data');
        $result = $dispatcher->dispatch($event);

        // The listener should have been called
        $this->assertInstanceOf(TestEvent::class, $result);
        $this->assertCount(1, $listener->getHandledEvents());
        $this->assertSame($event, $listener->getHandledEvents()[0]);
    }

    public function testMultipleEventListenersOfDifferentTypes(): void
    {
        $provider = new ListenerProvider();
        $dispatcher = new EventDispatcher($provider);

        $testEventListener = new TestEventListener();
        $anotherStoppableListener = new class implements EventListenerInterface {
            private array $handledEvents = [];

            public function handle(object $event): void
            {
                $this->handledEvents[] = $event;
            }
            
            public function __invoke(object $event): void
            {
                $this->handle($event);
            }

            public function getHandledEvents(): array
            {
                return $this->handledEvents;
            }
        };

        $provider->addListener(TestEvent::class, $testEventListener);
        $provider->addListener(StoppableTestEvent::class, $anotherStoppableListener);

        $testEvent = new TestEvent('test_data');
        $stoppableEvent = new StoppableTestEvent('stoppable_data');

        $dispatcher->dispatch($testEvent);
        $dispatcher->dispatch($stoppableEvent);

        $this->assertCount(1, $testEventListener->getHandledEvents());
        $this->assertCount(1, $anotherStoppableListener->getHandledEvents());
    }

    public function testEventListenerCanBeInvokedAsCallable(): void
    {
        $listener = new TestEventListener();
        $event = new TestEvent('invokable_test');

        // Test that the listener can be called as an invokable
        $listener($event);

        $this->assertCount(1, $listener->getHandledEvents());
        $this->assertSame($event, $listener->getHandledEvents()[0]);
    }
}