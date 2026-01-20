<?php

declare(strict_types=1);

namespace Tests;

use FastD\Event\EventDispatcher;
use FastD\Event\ListenerProvider;
use PHPUnit\Framework\TestCase;

class EventDispatcherTest extends TestCase
{
    public function testDispatchWithNoListeners(): void
    {
        $provider = new ListenerProvider();
        $dispatcher = new EventDispatcher($provider);
        $event = new TestEvent();

        $result = $dispatcher->dispatch($event);

        $this->assertSame($event, $result);
    }

    public function testDispatchWithListeners(): void
    {
        $provider = new ListenerProvider();
        $dispatcher = new EventDispatcher($provider);
        
        $listener = new SimpleEventListener();
        $provider->addListener($listener);
        
        $event = new TestEvent();
        
        // Clear log before test
        SimpleEventListener::clearProcessLog();
        
        $result = $dispatcher->dispatch($event);

        $this->assertSame($event, $result);
        $this->assertEquals(['processed'], SimpleEventListener::getProcessLog());
    }

    public function testDispatchStopsWhenPropagationStopped(): void
    {
        $provider = new ListenerProvider();
        $dispatcher = new EventDispatcher($provider);
        
        $listener = new SimpleEventListener();
        $provider->addListener($listener);
        
        $event = new TestEvent();
        $event->stopPropagation(); // Stop propagation before dispatch
        
        SimpleEventListener::clearProcessLog();
        
        $result = $dispatcher->dispatch($event);

        $this->assertSame($event, $result);
        // The listener should not be called since propagation is stopped
        $this->assertEmpty(SimpleEventListener::getProcessLog());
    }

    public function testDispatchHandlesException(): void
    {
        $provider = new ListenerProvider();
        $dispatcher = new EventDispatcher($provider);
        
        $throwingListener = new ThrowingEventListener();
        $provider->addListener($throwingListener);
        
        $event = new TestEvent();

        // This should not throw an exception because it's caught and handled by ThrowableListener
        $result = $dispatcher->dispatch($event);

        $this->assertInstanceOf(TestEvent::class, $result);
        $this->assertNotEmpty($result->getThrowables());
    }

    public function testDispatchStopsWhenExceptionStopped(): void
    {
        $provider = new ListenerProvider();
        $dispatcher = new EventDispatcher($provider);
        
        $throwingListener = new ThrowingEventListener();
        $provider->addListener($throwingListener);
        
        $event = new TestEvent();
        $event->stopException(); // Stop exception handling before dispatch

        $result = $dispatcher->dispatch($event);

        $this->assertSame($event, $result);
        $this->assertEmpty($result->getThrowables());
    }
}