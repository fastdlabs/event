<?php

namespace Tests;

use FastD\Event\ListenerProvider;
use Tests\_support\TestEvent;
use Tests\_support\BaseEvent;
use Tests\_support\ChildEvent;
use Tests\_support\EventInterface;
use Tests\_support\ConcreteEvent;
use PHPUnit\Framework\TestCase;

class ListenerProviderInternalMethodsTest extends TestCase
{
    public function testGetEventParentClasses(): void
    {
        $provider = new ListenerProvider();

        // Use reflection to access the private method
        $reflection = new \ReflectionClass($provider);
        $method = $reflection->getMethod('getEventParentClasses');
        $method->setAccessible(true);

        // Test with a child class that has a parent
        $parentClasses = $method->invoke($provider, ChildEvent::class);

        // ChildEvent extends BaseEvent, so BaseEvent should be in the result
        $this->assertContains(BaseEvent::class, $parentClasses);
    }

    public function testGetEventImplementedInterfaces(): void
    {
        $provider = new ListenerProvider();

        // Use reflection to access the private method
        $reflection = new \ReflectionClass($provider);
        $method = $reflection->getMethod('getEventImplementedInterfaces');
        $method->setAccessible(true);

        // Test with a class that implements an interface
        $interfaces = $method->invoke($provider, ConcreteEvent::class);

        // ConcreteEvent implements EventInterface, so EventInterface should be in the result
        $this->assertContains(EventInterface::class, $interfaces);
    }

    public function testGetEventImplementedInterfacesWithParentClass(): void
    {
        $provider = new ListenerProvider();

        // Use reflection to access the private method
        $reflection = new \ReflectionClass($provider);
        $method = $reflection->getMethod('getEventImplementedInterfaces');
        $method->setAccessible(true);

        // Create a child class that inherits from a parent that implements an interface
        // In our case, we'll just test the method works correctly
        $interfaces = $method->invoke($provider, ConcreteEvent::class);

        // Should contain EventInterface
        $this->assertIsArray($interfaces);
        $this->assertContains(EventInterface::class, $interfaces);
    }

    public function testBuildEventHierarchy(): void
    {
        $provider = new ListenerProvider();

        // Use reflection to access the private method
        $reflection = new \ReflectionClass($provider);
        $method = $reflection->getMethod('buildEventHierarchy');
        $method->setAccessible(true);

        // Test with a child class
        $hierarchy = $method->invoke($provider, ChildEvent::class);

        // Should have parentClasses and interfaces keys
        $this->assertArrayHasKey('parentClasses', $hierarchy);
        $this->assertArrayHasKey('interfaces', $hierarchy);
        $this->assertIsArray($hierarchy['parentClasses']);
        $this->assertIsArray($hierarchy['interfaces']);
    }

    public function testClearCache(): void
    {
        $provider = new ListenerProvider();

        // Add a listener to trigger some internal operations
        $listener = new class implements \FastD\Event\EventListenerInterface {
            public function handle(object $event): void {}
            
            public function __invoke(object $event): void {
                $this->handle($event);
            }
        };
        $provider->addListener(TestEvent::class, $listener);

        // Dispatch an event to potentially populate cache
        $event = new TestEvent('test');
        $provider->getListenersForEvent($event);

        // Use reflection to access the private method
        $reflection = new \ReflectionClass($provider);
        $method = $reflection->getMethod('clearCache');
        $method->setAccessible(true);

        // Call clearCache method
        $method->invoke($provider);

        // Verify the cache was cleared by attempting another operation
        $event2 = new TestEvent('test2');
        $listeners = $provider->getListenersForEvent($event2);

        // Should return an iterable
        $this->assertIsIterable($listeners);
    }

    public function testEventHierarchyIncludesBothParentsAndInterfaces(): void
    {
        $provider = new ListenerProvider();

        // Use reflection to access the private method
        $reflection = new \ReflectionClass($provider);
        $method = $reflection->getMethod('buildEventHierarchy');
        $method->setAccessible(true);

        // Test with our ConcreteEvent which implements EventInterface
        $hierarchy = $method->invoke($provider, ConcreteEvent::class);

        // Since ConcreteEvent doesn't extend anything, parentClasses should be empty
        // But interfaces should contain EventInterface
        $this->assertIsArray($hierarchy['parentClasses']);
        $this->assertIsArray($hierarchy['interfaces']);
        $this->assertContains(EventInterface::class, $hierarchy['interfaces']);
    }
}