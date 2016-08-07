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

/**
 * Interface EventInterface
 *
 * @package FastD\Event
 */
interface EventInterface
{
    const EVENT_BEFORE = 100;
    const EVENT_AFTER  = 0;

    /**
     * @param $name
     * @param $callable
     * @param $when
     * @param $bindTo
     * @return $this
     */
    public function on($name, $callable, $when = EventInterface::EVENT_BEFORE, $bindTo = null);

    /**
     * @param $name
     * @return $this
     */
    public function off($name);

    /**
     * @param $name
     * @param array $params
     * @return mixed
     */
    public function trigger($name, array $params = []);
}