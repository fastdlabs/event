<?php

namespace Tests;

use FastD\Event\ListenerProvider;
use Tests\_support\TestEvent;
use Tests\_support\BaseEvent;
use Tests\_support\ChildEvent;
use PHPUnit\Framework\TestCase;

class ListenerProviderTest extends TestCase
{
    public function testAddListenerAndGetListeners(): void
    {
        $provider = new ListenerProvider();

        $listener = function ($event) {
            // Do nothing
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

        $listener = function ($event) {
            // Do nothing
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
        $listenerLow = function ($event) use (&$results) {
            $results[] = 'low';
        };
        $listenerHigh = function ($event) use (&$results) {
            $results[] = 'high';
        };
        $listenerNormal = function ($event) use (&$results) {
            $results[] = 'normal';
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
        $this->assertEquals(['high', 'normal', 'low'], $results);
    }

    public function testExceptionThrownForNonExistentEventClass(): void
    {
        $provider = new ListenerProvider();

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Event class NonExistentClass does not exist or is not loaded');

        $provider->addListener('NonExistentClass', function () {});
    }
}