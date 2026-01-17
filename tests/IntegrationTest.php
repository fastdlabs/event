<?php

namespace Tests;

use FastD\Event\EventDispatcher;
use FastD\Event\ListenerProvider;
use FastD\Event\EventListenerInterface;
use FastD\Event\EventPriority;
use Tests\_support\TestEvent;
use Tests\_support\StoppableTestEvent;
use Tests\_support\BaseEvent;
use Tests\_support\ChildEvent;
use PHPUnit\Framework\TestCase;

class IntegrationTest extends TestCase
{
    public function testFullEventFlow(): void
    {
        $listenerProvider = new ListenerProvider();
        $eventDispatcher = new EventDispatcher($listenerProvider);

        $sharedState = new class() {
            public array $executionOrder = [];
        };
        
        // Register listeners with different priorities
        $listenerProvider->addListener(
            TestEvent::class,
            new class($sharedState) implements EventListenerInterface {
                private $shared;
                
                public function __construct($shared) {
                    $this->shared = $shared;
                }
                
                public function handle(object $event): void {
                    $this->shared->executionOrder[] = 'first';
                }
                
                public function __invoke(object $event): void {
                    $this->handle($event);
                }
            },
            EventPriority::HIGH
        );

        $listenerProvider->addListener(
            TestEvent::class,
            new class($sharedState) implements EventListenerInterface {
                private $shared;
                
                public function __construct($shared) {
                    $this->shared = $shared;
                }
                
                public function handle(object $event): void {
                    $this->shared->executionOrder[] = 'second';
                }
                
                public function __invoke(object $event): void {
                    $this->handle($event);
                }
            },
            EventPriority::NORMAL
        );

        $listenerProvider->addListener(
            TestEvent::class,
            new class($sharedState) implements EventListenerInterface {
                private $shared;
                
                public function __construct($shared) {
                    $this->shared = $shared;
                }
                
                public function handle(object $event): void {
                    $this->shared->executionOrder[] = 'third';
                }
                
                public function __invoke(object $event): void {
                    $this->handle($event);
                }
            },
            EventPriority::LOW
        );

        $event = new TestEvent('integration_test');
        $result = $eventDispatcher->dispatch($event);

        $this->assertSame($event, $result);
        $this->assertEquals(['first', 'second', 'third'], $sharedState->executionOrder);
    }

    public function testStoppableEventFlow(): void
    {
        $listenerProvider = new ListenerProvider();
        $eventDispatcher = new EventDispatcher($listenerProvider);

        $counter = new class() {
            public int $executionCount = 0;
        };

        $listenerProvider->addListener(
            StoppableTestEvent::class,
            new class($counter) implements EventListenerInterface {
                private $shared;
                
                public function __construct($shared) {
                    $this->shared = $shared;
                }
                
                public function handle(object $event): void {
                    $this->shared->executionCount++;
                    if (method_exists($event, 'stopPropagation')) {
                        $event->stopPropagation();
                    }
                }
                
                public function __invoke(object $event): void {
                    $this->handle($event);
                }
            }
        );

        $listenerProvider->addListener(
            StoppableTestEvent::class,
            new class($counter) implements EventListenerInterface {
                private $shared;
                
                public function __construct($shared) {
                    $this->shared = $shared;
                }
                
                public function handle(object $event): void {
                    $this->shared->executionCount++; // This should not be executed
                }
                
                public function __invoke(object $event): void {
                    $this->handle($event);
                }
            }
        );

        $event = new StoppableTestEvent('stoppable_test');
        $eventDispatcher->dispatch($event);

        $this->assertEquals(1, $counter->executionCount);
        $this->assertTrue($event->isPropagationStopped());
    }

    public function testEventInheritanceSupport(): void
    {
        $listenerProvider = new ListenerProvider();
        $eventDispatcher = new EventDispatcher($listenerProvider);

        $counter = new class() {
            public int $executionCount = 0;
        };

        // Register listener for base event class
        $listenerProvider->addListener(
            BaseEvent::class,
            new class($counter) implements EventListenerInterface {
                private $shared;
                
                public function __construct($shared) {
                    $this->shared = $shared;
                }
                
                public function handle(object $event): void {
                    $this->shared->executionCount++;
                }
                
                public function __invoke(object $event): void {
                    $this->handle($event);
                }
            }
        );

        // Dispatch child event - should trigger base event listener
        $event = new ChildEvent('inheritance_test');
        $eventDispatcher->dispatch($event);

        $this->assertEquals(1, $counter->executionCount);
    }
}