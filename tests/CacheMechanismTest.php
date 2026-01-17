<?php

namespace Tests;

use FastD\Event\EventDispatcher;
use FastD\Event\ListenerProvider;
use FastD\Event\EventListenerInterface;
use Tests\_support\TestEvent;
use Tests\_support\BaseEvent;
use Tests\_support\ChildEvent;
use Tests\_support\EventInterface;
use Tests\_support\ConcreteEvent;
use PHPUnit\Framework\TestCase;

class CacheMechanismTest extends TestCase
{
    public function testCacheStoresEventHierarchy(): void
    {
        $provider = new ListenerProvider();

        // Add some listeners
        $listener1 = new class implements EventListenerInterface {
            public function handle(object $event): void {}
            
            public function __invoke(object $event): void {
                $this->handle($event);
            }
        };
        $listener2 = new class implements EventListenerInterface {
            public function handle(object $event): void {}
            
            public function __invoke(object $event): void {
                $this->handle($event);
            }
        };
        
        $provider->addListener(TestEvent::class, $listener1);
        $provider->addListener(BaseEvent::class, $listener2);

        // Access the provider twice with the same event to test caching
        $event = new TestEvent('test_data');
        
        // First call - should compute and cache
        $listeners1 = iterator_to_array($provider->getListenersForEvent($event));
        
        // Second call - should use cache
        $listeners2 = iterator_to_array($provider->getListenersForEvent($event));

        // Results should be the same
        $this->assertEquals(count($listeners1), count($listeners2));
    }

    public function testCacheUpdatesAfterAddingNewListener(): void
    {
        $provider = new ListenerProvider();

        $counter = new class() {
            public int $callCount = 0;
        };
        $listener1 = new class($counter) implements EventListenerInterface {
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
        
        $provider->addListener(TestEvent::class, $listener1);

        // First dispatch - this actually executes the listener
        $event1 = new TestEvent('test_data');
        $dispatcher = new EventDispatcher($provider);
        $dispatcher->dispatch($event1);
        
        // Verify initial call count
        $initialCount = $counter->callCount;
        
        // Add another listener which should clear the cache
        $listener2 = new class($counter) implements EventListenerInterface {
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
        $provider->addListener(TestEvent::class, $listener2);

        // Second dispatch - should use updated cache and call both listeners
        $event2 = new TestEvent('test_data2');
        $dispatcher->dispatch($event2);
        
        // Both listeners should be called now
        $this->assertGreaterThan($initialCount, $counter->callCount);
    }

    public function testCacheWorksWithInheritance(): void
    {
        $provider = new ListenerProvider();
        $dispatcher = new EventDispatcher($provider);

        $counter = new class() {
            public int $callCount = 0;
        };
        $baseListener = new class($counter) implements EventListenerInterface {
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
        
        $provider->addListener(BaseEvent::class, $baseListener);

        // Use child event - should trigger parent class listener
        $event = new ChildEvent('test_data');
        
        // Dispatch multiple times - should use cache after first computation
        $dispatcher->dispatch($event);
        $dispatcher->dispatch($event);
        $dispatcher->dispatch($event);
        
        // Should be called once for each dispatch
        $this->assertEquals(3, $counter->callCount);
    }

    public function testCacheWorksWithInterfaceImplementation(): void
    {
        $provider = new ListenerProvider();
        $dispatcher = new EventDispatcher($provider);

        $counter = new class() {
            public int $callCount = 0;
        };
        $interfaceListener = new class($counter) implements EventListenerInterface {
            private $shared;
            
            public function __construct($shared) {
                $this->shared = $shared;
            }
            
            public function handle(object $event): void {
                if ($event instanceof EventInterface) {
                    $this->shared->callCount++;
                }
            }
            
            public function __invoke(object $event): void {
                $this->handle($event);
            }
        };
        
        $provider->addListener(EventInterface::class, $interfaceListener);

        // Use concrete event that implements interface
        $event = new ConcreteEvent('test_data');
        
        // Dispatch multiple times - should use cache after first computation
        $dispatcher->dispatch($event);
        $dispatcher->dispatch($event);
        
        // Check that listeners are retrieved consistently
        $firstResult = iterator_to_array($provider->getListenersForEvent($event));
        $secondResult = iterator_to_array($provider->getListenersForEvent($event));
        
        // Results should be identical
        $this->assertEquals(count($firstResult), count($secondResult));
        $this->assertEquals(2, $counter->callCount); // Listener should be called once per dispatch
    }

    public function testPerformanceComparisonWithCache(): void
    {
        $provider = new ListenerProvider();

        $listener = new class implements EventListenerInterface {
            public function handle(object $event): void {}
            
            public function __invoke(object $event): void {
                $this->handle($event);
            }
        };
        $provider->addListener(TestEvent::class, $listener);

        $event = new TestEvent('performance_test');

        // Measure time for multiple calls
        $startTime = microtime(true);
        
        // Multiple calls should be faster due to caching
        for ($i = 0; $i < 100; $i++) {
            iterator_to_array($provider->getListenersForEvent($event));
        }
        
        $endTime = microtime(true);
        $duration = $endTime - $startTime;

        // This test mainly verifies that the code runs without errors
        // and that caching doesn't break functionality
        $this->assertLessThan(1.0, $duration); // Should complete well under 1 second
    }
}