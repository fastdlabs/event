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
 * Class EventDispatcher
 *
 * @package FastD\Event
 */
class EventDispatcher extends EventManager
{
    /**
     * @param $name
     * @param $callback
     * @return $this
     */
    public function on($name, $callback)
    {
        parent::attach($name, $callback);

        $this->events[$name]->attach($callback);

        return $this;
    }

    /**
     * @param $name
     * @return $this
     */
    public function off($name)
    {
        parent::detach($name);

        return $this;
    }

    /**
     * @param $name
     * @param array $arguments
     * @return mixed
     */
    public function dispatch($name, array $arguments = [])
    {
        return parent::trigger($name, null, $arguments);
    }
}