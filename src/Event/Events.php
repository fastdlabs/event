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
use InvalidArgumentException;

/**
 * Class Event
 *
 * @package FastD\Event
 */
class Events
{
    /**
     * @var array
     */
    protected $events = [];

    /**
     * @param $name
     * @param $callback
     * @return $this
     */
    public function on($name, $callback)
    {
        $this->events[$name] = $callback;

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
     * @param array $args
     * @return mixed
     */
    public function trigger($name, array $args = [])
    {
        if (!isset($this->events[$name])) {
            throw new InvalidArgumentException(sprintf('Event "%s" is undefined.', $name));
        }

        return call_user_func_array($this->events[$name], $args);
    }
}