<?php

namespace Tests;

use FastD\Event\EventDispatcher;
use FastD\Event\ListenerProvider;
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

        $called = false;
        $listener = function ($event) use (&$called) {
            $called = true;
        };

        $listenerProvider->addListener(TestEvent::class, $listener);

        $event = new TestEvent('test_data');
        $dispatcher->dispatch($event);

        $this->assertTrue($called);
    }

    public function testDispatchWithMultipleListeners(): void
    {
        $listenerProvider = new ListenerProvider();
        $dispatcher = new EventDispatcher($listenerProvider);

        $callCount = 0;
        $listener1 = function ($event) use (&$callCount) {
            $callCount++;
        };
        $listener2 = function ($event) use (&$callCount) {
            $callCount++;
        };

        $listenerProvider->addListener(TestEvent::class, $listener1);
        $listenerProvider->addListener(TestEvent::class, $listener2);

        $event = new TestEvent('test_data');
        $dispatcher->dispatch($event);

        $this->assertEquals(2, $callCount);
    }

    public function testDispatchWithStoppableEvent(): void
    {
        $listenerProvider = new ListenerProvider();
        $dispatcher = new EventDispatcher($listenerProvider);

        $callCount = 0;
        $listener1 = function ($event) use (&$callCount) {
            $callCount++;
            $event->stopPropagation();
        };
        $listener2 = function ($event) use (&$callCount) {
            $callCount++;
        };

        $listenerProvider->addListener(StoppableTestEvent::class, $listener1);
        $listenerProvider->addListener(StoppableTestEvent::class, $listener2);

        $event = new StoppableTestEvent('test_data');
        $dispatcher->dispatch($event);

        $this->assertEquals(1, $callCount);
    }
}