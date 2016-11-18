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

        return $this;
    }

    /**
     * @param $name
     * @param $callback
     * @return $this
     */
    public function off($name, $callback)
    {
        parent::detach($name, $callback);

        return $this;
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
    public function dispatch($event, array $argv = [])
    {
        return parent::trigger($event, $argv);
    }
}