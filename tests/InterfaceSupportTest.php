<?php

namespace Tests;

use FastD\Event\EventDispatcher;
use FastD\Event\ListenerProvider;
use FastD\Event\EventListenerInterface;
use Tests\_support\EventInterface;
use Tests\_support\ConcreteEvent;
use PHPUnit\Framework\TestCase;

class InterfaceSupportTest extends TestCase
{
    public function testListenerRegisteredOnInterfaceGetsCalledForImplementation(): void
    {
        $provider = new ListenerProvider();
        $dispatcher = new EventDispatcher($provider);

        $sharedState = new class() {
            public bool $called = false;
        };
        $listener = new class($sharedState) implements EventListenerInterface {
            private $shared;
            
            public function __construct($shared) {
                $this->shared = $shared;
            }
            
            public function handle(object $event): void {
                if ($event instanceof EventInterface) {
                    $this->shared->called = true;
                }
            }
            
            public function __invoke(object $event): void {
                $this->handle($event);
            }
        };

        // Register listener for interface
        $provider->addListener(EventInterface::class, $listener);

        // Dispatch concrete implementation
        $event = new ConcreteEvent('test_data');
        $dispatcher->dispatch($event);

        $this->assertTrue($sharedState->called);
    }

    public function testMultipleListenersForInterfaceAndImplementation(): void
    {
        $provider = new ListenerProvider();
        $dispatcher = new EventDispatcher($provider);

        $sharedCounter = new class() {
            public int $interfaceCallCount = 0;
            public int $implementationCallCount = 0;
        };

        // Register listener for interface
        $interfaceListener = new class($sharedCounter) implements EventListenerInterface {
            private $shared;
            
            public function __construct($shared) {
                $this->shared = $shared;
            }
            
            public function handle(object $event): void {
                if ($event instanceof EventInterface) {
                    $this->shared->interfaceCallCount++;
                }
            }
            
            public function __invoke(object $event): void {
                $this->handle($event);
            }
        };
        $provider->addListener(EventInterface::class, $interfaceListener);

        // Register listener for concrete implementation
        $implementationListener = new class($sharedCounter) implements EventListenerInterface {
            private $shared;
            
            public function __construct($shared) {
                $this->shared = $shared;
            }
            
            public function handle(object $event): void {
                if ($event instanceof ConcreteEvent) {
                    $this->shared->implementationCallCount++;
                }
            }
            
            public function __invoke(object $event): void {
                $this->handle($event);
            }
        };
        $provider->addListener(ConcreteEvent::class, $implementationListener);

        // Dispatch concrete event
        $event = new ConcreteEvent('test_data');
        $dispatcher->dispatch($event);

        // Both listeners should be called
        $this->assertEquals(1, $sharedCounter->interfaceCallCount);
        $this->assertEquals(1, $sharedCounter->implementationCallCount);
    }

    public function testInterfaceListenersWorkWithPriorities(): void
    {
        $provider = new ListenerProvider();
        $dispatcher = new EventDispatcher($provider);

        $sharedState = new class() {
            public array $executionOrder = [];
        };

        // Register high priority listener for interface
        $highPriorityListener = new class($sharedState) implements EventListenerInterface {
            private $shared;
            
            public function __construct($shared) {
                $this->shared = $shared;
            }
            
            public function handle(object $event): void {
                if ($event instanceof EventInterface) {
                    $this->shared->executionOrder[] = 'high_priority_interface';
                }
            }
            
            public function __invoke(object $event): void {
                $this->handle($event);
            }
        };
        $provider->addListener(EventInterface::class, $highPriorityListener, 100);

        // Register low priority listener for interface
        $lowPriorityListener = new class($sharedState) implements EventListenerInterface {
            private $shared;
            
            public function __construct($shared) {
                $this->shared = $shared;
            }
            
            public function handle(object $event): void {
                if ($event instanceof EventInterface) {
                    $this->shared->executionOrder[] = 'low_priority_interface';
                }
            }
            
            public function __invoke(object $event): void {
                $this->handle($event);
            }
        };
        $provider->addListener(EventInterface::class, $lowPriorityListener, -100);

        // Dispatch concrete event
        $event = new ConcreteEvent('test_data');
        $dispatcher->dispatch($event);

        // High priority should execute first
        $this->assertEquals(['high_priority_interface', 'low_priority_interface'], $sharedState->executionOrder);
    }

    public function testInterfaceInheritanceWorks(): void
    {
        $provider = new ListenerProvider();
        $dispatcher = new EventDispatcher($provider);

        // Create a child interface
        $counter = new class() {
            public int $interfaceCallCount = 0;
        };
        $interfaceListener = new class($counter) implements EventListenerInterface {
            private $shared;
            
            public function __construct($shared) {
                $this->shared = $shared;
            }
            
            public function handle(object $event): void {
                if ($event instanceof EventInterface) {
                    $this->shared->interfaceCallCount++;
                }
            }
            
            public function __invoke(object $event): void {
                $this->handle($event);
            }
        };
        $provider->addListener(EventInterface::class, $interfaceListener);

        // Dispatch event that implements the interface
        $event = new ConcreteEvent('test_data');
        $dispatcher->dispatch($event);

        $this->assertEquals(1, $counter->interfaceCallCount);
    }
}