<?php
/**
 *
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

namespace FastD\Event;

use FastD\Event\Exceptions\EventUndefinedException;

/**
 * Class Event
 *
 * @package FastD\Event
 */
class Event implements EventInterface
{
    /**
     * @var array
     */
    protected $events = [];

    /**
     * @var array
     */
    protected $bindTos = [];

    /**
     * @param $name
     * @param $callable
     * @param $when
     * @param $bindTo
     * @return $this
     */
    public function on($name, $callable, $when = EventInterface::EVENT_BEFORE, $bindTo = null)
    {
        $this->events[$name] = $callable;

        if (null !== $bindTo) {
            $this->bindTo($bindTo, $name);
        }

        return $this;
    }

    /**
     * @param $from
     * @param $event
     * @return $this
     */
    public function bindTo($from, $event)
    {
        if (is_array($from)) {
            $from = get_class($from[0]) . '::' . $from[1];
        }

        $this->bindTos[$from][] = $event;

        return $this;
    }

    /**
     * @param $name
     * @return $this
     */
    public function off($name)
    {
        if (isset($this->events[$name])) {
            unset($this->events[$name]);
        }

        return $this;
    }

    /**
     * @param $name
     * @return string
     */
    protected function getEventHandleName($name)
    {
        return 'on' . ucfirst($name);
    }

    /**
     * @param $name
     * @param array $params
     * @return mixed
     * @throws EventUndefinedException
     */
    public function trigger($name, array $params = [])
    {
        if (!isset($this->events[$name])) {
            $handle = $this->getEventHandleName($name);
            if (method_exists($this, $handle)) {
                return call_user_func_array([$this, $handle], $params);
            }
            throw new EventUndefinedException($name);
        }

        return call_user_func_array($this->events[$name], $params);
    }
}