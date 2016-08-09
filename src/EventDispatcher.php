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
 * Class EventDispatcher
 *
 * @package FastD\Event
 */
class EventDispatcher
{
    const DOT = '.';

    /**
     * @var array
     */
    protected $events = [];

    /**
     * @param $name
     * @param $callable
     * @return $this
     */
    public function on($name, $callable)
    {
        $this->events[$name] = $callable;

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
     * @param array $params
     * @return mixed
     * @throws EventUndefinedException
     */
    public function trigger($name, array $params = [])
    {
        if (!isset($this->events[$name])) {
            if (false === strpos($name, static::DOT)) {
                throw new EventUndefinedException($name);
            }
            list($name, $handle) = explode(static::DOT, $name);
            if (!isset($this->events[$name])) {
                throw new EventUndefinedException($name);
            }
            $obj = $this->events[$name];
            $handle = 'on' . ucfirst($handle);
            if (method_exists($obj, $handle)) {
                return call_user_func_array([$obj, $handle], $params);
            }
        }
        return call_user_func_array($this->events[$name], $params);
    }
}