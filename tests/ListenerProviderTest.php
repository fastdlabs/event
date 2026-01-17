<?php

namespace Tests;

use FastD\Event\ListenerProvider;
use FastD\Event\EventListenerInterface;
use Tests\_support\TestEvent;
use Tests\_support\BaseEvent;
use Tests\_support\ChildEvent;
use PHPUnit\Framework\TestCase;

class ListenerProviderTest extends TestCase
{
    public function testAddListenerAndGetListeners(): void
    {
        $provider = new ListenerProvider();

        $listener = new class implements EventListenerInterface {
            public function handle(object $event): void {}
            
            public function __invoke(object $event): void {
                $this->handle($event);
            }
        };

        $provider->addListener(TestEvent::class, $listener);

        $listeners = iterator_to_array($provider->getListenersForEvent(new TestEvent('test')));

        $this->assertCount(1, $listeners);
        $this->assertSame($listener, $listeners[0]);
    }

    public function testGetListenersForEventWithNoListeners(): void
    {
        $provider = new ListenerProvider();

        $listeners = iterator_to_array($provider->getListenersForEvent(new TestEvent('test')));

        $this->assertEmpty($listeners);
    }

    public function testGetListenersForInheritedEvent(): void
    {
        $provider = new ListenerProvider();

        $listener = new class implements EventListenerInterface {
            public function handle(object $event): void {}
            
            public function __invoke(object $event): void {
                $this->handle($event);
            }
        };

        $provider->addListener(BaseEvent::class, $listener);

        $childEvent = new ChildEvent('test');
        $listeners = iterator_to_array($provider->getListenersForEvent($childEvent));

        $this->assertCount(1, $listeners);
        $this->assertSame($listener, $listeners[0]);
    }

    public function testListenerPriorities(): void
    {
        $provider = new ListenerProvider();

        $results = [];
        $sharedResults = new class() {
            public array $results = [];
        };
        
        $listenerLow = new class($sharedResults) implements EventListenerInterface {
            private $shared;
            
            public function __construct($shared) {
                $this->shared = $shared;
            }
            
            public function handle(object $event): void {
                $this->shared->results[] = 'low';
            }
            
            public function __invoke(object $event): void {
                $this->handle($event);
            }
        };
        $listenerHigh = new class($sharedResults) implements EventListenerInterface {
            private $shared;
            
            public function __construct($shared) {
                $this->shared = $shared;
            }
            
            public function handle(object $event): void {
                $this->shared->results[] = 'high';
            }
            
            public function __invoke(object $event): void {
                $this->handle($event);
            }
        };
        $listenerNormal = new class($sharedResults) implements EventListenerInterface {
            private $shared;
            
            public function __construct($shared) {
                $this->shared = $shared;
            }
            
            public function handle(object $event): void {
                $this->shared->results[] = 'normal';
            }
            
            public function __invoke(object $event): void {
                $this->handle($event);
            }
        };

        // Add listeners with different priorities
        $provider->addListener(TestEvent::class, $listenerLow, -10);       // Low priority
        $provider->addListener(TestEvent::class, $listenerHigh, 10);       // High priority
        $provider->addListener(TestEvent::class, $listenerNormal, 0);      // Normal priority

        $event = new TestEvent('test');
        $listeners = iterator_to_array($provider->getListenersForEvent($event));

        // Execute all listeners
        foreach ($listeners as $listener) {
            $listener($event);
        }

        // High priority should execute first, then normal, then low
        $this->assertEquals(['high', 'normal', 'low'], $sharedResults->results);
    }

    public function testExceptionThrownForNonExistentEventClass(): void
    {
        $provider = new ListenerProvider();

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Event class NonExistentClass does not exist or is not loaded');

        $listener = new class implements EventListenerInterface {
            public function handle(object $event): void {}
            
            public function __invoke(object $event): void {
                $this->handle($event);
            }
        };
        
        $provider->addListener('NonExistentClass', $listener);
    }
}