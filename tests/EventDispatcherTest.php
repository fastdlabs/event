<?php

namespace Tests;

use FastD\Event\EventDispatcher;
use FastD\Event\ListenerProvider;
use FastD\Event\EventListenerInterface;
use Tests\_support\TestEvent;
use Tests\_support\StoppableTestEvent;
use PHPUnit\Framework\TestCase;

class EventDispatcherTest extends TestCase
{
    public function testDispatchReturnsSameEventObject(): void
    {
        $listenerProvider = new ListenerProvider();
        $dispatcher = new EventDispatcher($listenerProvider);

        $event = new TestEvent('test_data');

        $result = $dispatcher->dispatch($event);

        $this->assertSame($event, $result);
    }

    public function testDispatchCallsRegisteredListeners(): void
    {
        $listenerProvider = new ListenerProvider();
        $dispatcher = new EventDispatcher($listenerProvider);

        $sharedState = new class() {
            public bool $called = false;
        };
        $listener = new class($sharedState) implements EventListenerInterface {
            private $shared;
            
            public function __construct($shared) {
                $this->shared = $shared;
            }
            
            public function handle(object $event): void {
                $this->shared->called = true;
            }
            
            public function __invoke(object $event): void {
                $this->handle($event);
            }
        };

        $listenerProvider->addListener(TestEvent::class, $listener);

        $event = new TestEvent('test_data');
        $dispatcher->dispatch($event);

        $this->assertTrue($sharedState->called);
    }

    public function testDispatchWithMultipleListeners(): void
    {
        $listenerProvider = new ListenerProvider();
        $dispatcher = new EventDispatcher($listenerProvider);

        $sharedCounter = new class() {
            public int $callCount = 0;
        };
        $listener1 = new class($sharedCounter) implements EventListenerInterface {
            private $shared;
            
            public function __construct($shared) {
                $this->shared = $shared;
            }
            
            public function handle(object $event): void {
                $this->shared->callCount++;
            }
            
            public function __invoke(object $event): void {
                $this->handle($event);
            }
        };
        $listener2 = new class($sharedCounter) implements EventListenerInterface {
            private $shared;
            
            public function __construct($shared) {
                $this->shared = $shared;
            }
            
            public function handle(object $event): void {
                $this->shared->callCount++;
            }
            
            public function __invoke(object $event): void {
                $this->handle($event);
            }
        };

        $listenerProvider->addListener(TestEvent::class, $listener1);
        $listenerProvider->addListener(TestEvent::class, $listener2);

        $event = new TestEvent('test_data');
        $dispatcher->dispatch($event);

        $this->assertEquals(2, $sharedCounter->callCount);
    }

    public function testDispatchWithStoppableEvent(): void
    {
        $listenerProvider = new ListenerProvider();
        $dispatcher = new EventDispatcher($listenerProvider);

        $sharedCounter = new class() {
            public int $callCount = 0;
        };
        $listener1 = new class($sharedCounter) implements EventListenerInterface {
            private $shared;
            
            public function __construct($shared) {
                $this->shared = $shared;
            }
            
            public function handle(object $event): void {
                $this->shared->callCount++;
                if (method_exists($event, 'stopPropagation')) {
                    $event->stopPropagation();
                }
            }
            
            public function __invoke(object $event): void {
                $this->handle($event);
            }
        };
        $listener2 = new class($sharedCounter) implements EventListenerInterface {
            private $shared;
            
            public function __construct($shared) {
                $this->shared = $shared;
            }
            
            public function handle(object $event): void {
                $this->shared->callCount++;
            }
            
            public function __invoke(object $event): void {
                $this->handle($event);
            }
        };

        $listenerProvider->addListener(StoppableTestEvent::class, $listener1);
        $listenerProvider->addListener(StoppableTestEvent::class, $listener2);

        $event = new StoppableTestEvent('test_data');
        $dispatcher->dispatch($event);

        $this->assertEquals(1, $sharedCounter->callCount);
    }
}