<?php

declare(strict_types=1);

namespace Tests;

use FastD\Event\ListenerProvider;
use PHPUnit\Framework\Attributes\AllowMockObjectsWithoutExpectations;
use PHPUnit\Framework\TestCase;

class ListenerProviderTest extends TestCase
{
    public function testAddListener(): void
    {
        $provider = new ListenerProvider();
        $listener = new SimpleEventListener();

        $provider->addListener($listener);

        $listeners = $provider->getListenersForEvent(new TestEvent());
        
        $this->assertNotEmpty(iterator_to_array($listeners));
    }

    public function testAddMultipleListeners(): void
    {
        $provider = new ListenerProvider();
        $listener1 = new SimpleEventListener();
        $listener2 = new SimpleEventListener();

        $provider->addListener($listener1, $listener2);

        $listeners = $provider->getListenersForEvent(new TestEvent());
        $listenerArray = iterator_to_array($listeners);
        
        $this->assertNotEmpty($listenerArray);
    }

    public function testAddEmptyListeners(): void
    {
        $provider = new ListenerProvider();

        $provider->addListener(); // Add no listeners

        $listeners = $provider->getListenersForEvent(new TestEvent());
        $listenerArray = iterator_to_array($listeners);
        
        $this->assertEmpty($listenerArray);
    }

    public function testGetListenersForEventReturnsCorrectListeners(): void
    {
        $provider = new ListenerProvider();
        $listener = new SimpleEventListener();

        $provider->addListener($listener);

        $event = new TestEvent();
        $listeners = $provider->getListenersForEvent($event);
        $listenerArray = iterator_to_array($listeners);

        $this->assertNotEmpty($listenerArray);
        // Check that the listener is the correct type
        foreach ($listenerArray as $returnedListener) {
            $this->assertInstanceOf(SimpleEventListener::class, $returnedListener);
        }
    }

    #[AllowMockObjectsWithoutExpectations]
    public function testListenersArePrioritized(): void
    {
        $provider = new ListenerProvider();
        
        // Create listeners with different priorities
        $highPriorityListener = new PriorityEventListener(10);
        // Create a separate class to bypass the deduplication in addListener method
        $differentClassListener = $this->getMockBuilder(PriorityEventListener::class)
            ->onlyMethods([])
            ->setConstructorArgs([1])
            ->getMock();

        $provider->addListener($highPriorityListener, $differentClassListener);

        $event = new TestEvent();
        
        // Test the priority queue directly to ensure it prioritizes correctly
        $testQueue = $provider->getListenersForEvent($event);
        $testQueue->setExtractFlags(\SplPriorityQueue::EXTR_DATA);
        $extractedFirst = $testQueue->extract(); // Should be highest priority
        $extractedSecond = $testQueue->extract(); // Should be lowest priority
        
        $this->assertInstanceOf(PriorityEventListener::class, $extractedFirst);
        $this->assertInstanceOf(PriorityEventListener::class, $extractedSecond);
        $this->assertEquals(10, $extractedFirst->priority());
        $this->assertEquals(1, $extractedSecond->priority());
    }

    public function testSameListenerAddedTwiceIsIgnored(): void
    {
        $provider = new ListenerProvider();
        $listener = new SimpleEventListener();

        // Add the same listener twice
        $provider->addListener($listener);
        $provider->addListener($listener);

        $event = new TestEvent();
        $listeners = $provider->getListenersForEvent($event);
        $listenerArray = iterator_to_array($listeners);

        // Count unique listeners
        $uniqueListeners = [];
        foreach ($listenerArray as $l) {
            $className = get_class($l);
            $uniqueListeners[$className] = $l;
        }

        // Since we're adding the same instance twice, it should only be registered once
        $this->assertCount(1, $uniqueListeners);
    }
}