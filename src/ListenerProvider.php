<?php

declare(strict_types=1);

namespace FastD\Event;

use Psr\EventDispatcher\ListenerProviderInterface;
use ReflectionClass;

class ListenerProvider implements ListenerProviderInterface
{
    private array $listeners = [];
    
    // Cache for event class hierarchies to improve performance
    private array $eventHierarchyCache = [];

    public function addListener(string $eventClass, EventListenerInterface $listener, int $priority = 0): void
    {
        if (!class_exists($eventClass) && !interface_exists($eventClass)) {
            throw new \InvalidArgumentException("Event class {$eventClass} does not exist or is not loaded");
        }

        if (!isset($this->listeners[$eventClass])) {
            $this->listeners[$eventClass] = [];
        }

        if (!isset($this->listeners[$eventClass][$priority])) {
            $this->listeners[$eventClass][$priority] = [];
        }

        $this->listeners[$eventClass][$priority][] = $listener;
        
        // Clear cache when adding new listener to ensure fresh lookups
        $this->clearCache();
    }

    public function getListenersForEvent(object $event): iterable
    {
        $eventClass = get_class($event);
        
        // Use cache if available
        if (isset($this->eventHierarchyCache[$eventClass])) {
            $hierarchy = $this->eventHierarchyCache[$eventClass];
        } else {
            $hierarchy = $this->buildEventHierarchy($eventClass);
            $this->eventHierarchyCache[$eventClass] = $hierarchy;
        }
        
        $listeners = [];
        
        // Add listeners for the exact event class
        if (isset($this->listeners[$eventClass])) {
            $listeners = array_merge($listeners, $this->mergeListenersByPriority($this->listeners[$eventClass]));
        }
        
        // Add listeners for parent classes
        foreach ($hierarchy['parentClasses'] as $parentClass) {
            if (isset($this->listeners[$parentClass])) {
                $parentListeners = $this->mergeListenersByPriority($this->listeners[$parentClass]);
                $listeners = array_merge($listeners, $parentListeners);
            }
        }
        
        // Add listeners for implemented interfaces
        foreach ($hierarchy['interfaces'] as $interface) {
            if (isset($this->listeners[$interface])) {
                $interfaceListeners = $this->mergeListenersByPriority($this->listeners[$interface]);
                $listeners = array_merge($listeners, $interfaceListeners);
            }
        }

        return $listeners;
    }

    private function mergeListenersByPriority(array $listenersByPriority): array
    {
        $result = [];
        
        krsort($listenersByPriority);
        
        foreach ($listenersByPriority as $priorityListeners) {
            $result = array_merge($result, $priorityListeners);
        }
        
        return $result;
    }

    private function buildEventHierarchy(string $eventClass): array
    {
        return [
            'parentClasses' => $this->getEventParentClasses($eventClass),
            'interfaces' => $this->getEventImplementedInterfaces($eventClass)
        ];
    }
    
    private function getEventParentClasses(string $eventClass): array
    {
        $parentClasses = [];
        $reflectionClass = new ReflectionClass($eventClass);

        while ($parentClass = $reflectionClass->getParentClass()) {
            $parentClassName = $parentClass->getName();
            $parentClasses[] = $parentClassName;
            $reflectionClass = $parentClass;
        }

        return $parentClasses;
    }
    
    private function getEventImplementedInterfaces(string $eventClass): array
    {
        $reflectionClass = new ReflectionClass($eventClass);
        $interfaces = [];
        
        // Get all interfaces implemented by the class
        $classInterfaces = $reflectionClass->getInterfaceNames();
        
        // Get interfaces from parent classes as well
        $parentClass = $reflectionClass->getParentClass();
        while ($parentClass !== false) {
            $interfaces = array_merge($interfaces, $parentClass->getInterfaceNames());
            $parentClass = $parentClass->getParentClass();
        }
        
        return array_unique(array_merge($classInterfaces, $interfaces));
    }
    
    private function clearCache(): void
    {
        $this->eventHierarchyCache = [];
    }
}