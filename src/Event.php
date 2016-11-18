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
 * Class Event
 *
 * @package FastD\Event
 */
class Event implements EventSubjectInterface, EventInterface
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var array|string|callable|object
     */
    protected $target;

    /**
     * @var array
     */
    protected $params = [];

    /**
     * @var bool
     */
    protected $propagation;

    /**
     * @var EventListenerInterface[]
     */
    protected $listeners = [];

    /**
     * Event constructor.
     *
     * @param $name
     * @param $target
     * @param array $arguments
     */
    public function __construct($name, $target, array $arguments = [])
    {
        $this->setName($name);
        $this->setTarget($target);
        $this->setParams($arguments);
    }

    /**
     * Get event name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get target/context from which event was triggered
     *
     * @return null|string|object
     */
    public function getTarget()
    {
        return $this->target;
    }

    /**
     * Get parameters passed to the event
     *
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * Get a single parameter by name
     *
     * @param  string $name
     * @return mixed
     */
    public function getParam($name)
    {
        return isset($this->params[$name]) ? $this->params[$name] : false;
    }

    /**
     * Set the event name
     *
     * @param  string $name
     * @return void
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Set the event target
     *
     * @param  null|string|object $target
     * @return void
     */
    public function setTarget($target)
    {
        $this->target = $target;
    }

    /**
     * Set event parameters
     *
     * @param  array $params
     * @return void
     */
    public function setParams(array $params)
    {
        $this->params = $params;
    }

    /**
     * Indicate whether or not to stop propagating this event
     *
     * @param  bool $flag
     */
    public function stopPropagation($flag)
    {
        $this->propagation = $flag;
    }

    /**
     * Has this event indicated event propagation should stop?
     *
     * @return bool
     */
    public function isPropagationStopped()
    {
        return $this->propagation;
    }

    /**
     * @param EventListenerInterface $eventListener
     */
    public function attach(EventListenerInterface $eventListener)
    {
        $this->listeners[spl_object_hash($eventListener)] = $eventListener;
    }

    /**
     * @param EventListenerInterface $eventListener
     */
    public function detach(EventListenerInterface $eventListener)
    {
        $hash = spl_object_hash($eventListener);

        if (isset($this->listeners[$hash])) {
            unset($this->listeners[$hash]);
        }
    }

    /**
     * @param array $arguments
     * @return array
     */
    public function broadcast(array $arguments = [])
    {
        $result = [];
        foreach ($this->listeners as $listener) {
            $result[] = call_user_func_array([$listener, 'handle'], [$this, $arguments]);
        }
        return $result;
    }
}