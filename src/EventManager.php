<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

namespace FastD\Event;

use FastD\Event\Exceptions\EventNotFoundException;

/**
 * Class EventManager
 *
 * @package FastD\Event
 */
abstract class EventManager implements EventManagerInterface
{
    /**
     * @var EventInterface[]
     */
    protected $events = [];

    /**
     * @param $name
     * @return EventInterface|EventBroadcastInterface
     * @throws EventNotFoundException
     */
    public function getEvent($name)
    {
        if (!isset($this->events[$name])) {
            throw new EventNotFoundException($name);
        }

        return $this->events[$name];
    }

    /**
     * Attaches a listener to an event
     *
     * @param string $event      the event to attach too
     * @param callable $callback a callable function
     * @param int $priority      the priority at which the $callback executed
     * @return bool true on success false on failure
     */
    public function attach($event, $callback, $priority = 0)
    {
        $this->events[$event] = new Event($event, $callback);

        return true;
    }

    /**
     * Detaches a listener from an event
     *
     * @param string $event      the event to attach too
     * @param callable $callback a callable function
     * @return bool true on success false on failure
     */
    public function detach($event, $callback = null)
    {
        return $this->getEvent($event)->detachListener($callback);
    }

    /**
     * Clear all listeners for a given event
     *
     * @param  string $event
     * @return bool
     */
    public function clearListeners($event)
    {
        return $this->getEvent($event)->cleanListeners();
    }

    /**
     * Trigger an event
     *
     * Can accept an EventInterface or will create one if not passed
     *
     * @param  string|EventInterface $event
     * @param  array|object $argv
     * @return mixed
     */
    public function trigger($event, array $argv = [])
    {
        $event =  $this->getEvent($event);

        if (!empty($argv)) {
            $event->setParams($argv);
        }

        $result = $event->broadcast();

        return array_pop($result);
    }
}