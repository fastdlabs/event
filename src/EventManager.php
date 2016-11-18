<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

namespace FastD\Event;

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
        if (isset($this->events[$event])) {
            unset($this->events[$event]);
        }
    }

    /**
     * Clear all listeners for a given event
     *
     * @param  string $event
     * @return void
     */
    public function clearListeners($event)
    {

    }

    /**
     * Trigger an event
     *
     * Can accept an EventInterface or will create one if not passed
     *
     * @param  string|EventInterface $event
     * @param  object|string $target
     * @param  array|object $argv
     * @return mixed
     */
    public function trigger($event, $target = null, $argv = array())
    {
        $result = $this->events[$event]->broadcast($argv);

        return array_pop($result);
    }
}