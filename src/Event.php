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
class Event implements EventInterface, EventBroadcastInterface
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var array[]
     */
    protected $listeners = [];

    /**
     * @var array
     */
    protected $params = [];

    /**
     * @var bool
     */
    protected $propagation = false;

    /**
     * Event constructor.
     *
     * @param $name
     * @param $listener
     * @param array $arguments
     */
    public function __construct($name, $listener, array $arguments = [])
    {
        $this->setName($name);
        $this->setListener($listener);
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
    public function getListeners()
    {
        return $this->listeners;
    }

    /**
     * Set the event target
     *
     * @param  null|string|object $listener
     * @return EventInterface
     */
    public function setListener($listener)
    {
        $this->listeners[] = $listener;

        return $this;
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
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Set event parameters
     *
     * @param  array $params
     * @return $this
     */
    public function setParams(array $params)
    {
        $this->params = $params;

        return $this;
    }

    /**
     * Indicate whether or not to stop propagating this event
     *
     * @param  bool $flag
     * @return $this
     */
    public function stopPropagation($flag)
    {
        $this->propagation = $flag;

        return $this;
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
     * @return bool
     */
    public function cleanListeners()
    {
        $this->listeners = [];

        return true;
    }

    /**
     * @param $index
     * @return bool
     */
    public function detachListener($index)
    {
        if ($this->listeners[$index]) {
            unset($this->listeners[$index]);
        }

        return true;
    }

    /**
     * @return array
     */
    public function broadcast()
    {
        $result = [];
        foreach ($this->listeners as $listener) {
            if (!$this->isPropagationStopped()) {
                switch ($listener) {
                    case ($listener instanceof EventListenerInterface):
                        $result[] = call_user_func_array([$listener, 'handle'], [$this, $this->getParams()]);
                        break;
                    case (is_callable($listener) || is_array($listener)):
                    default:
                        $result[] = call_user_func_array($listener, [$this, $this->getParams()]);
                }
            }
        }
        return $result;
    }
}