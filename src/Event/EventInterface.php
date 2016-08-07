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
    const EVENT_HEIGHT = 100;
    const EVENT_LOW = 10;

    /**
     * @param $name
     * @param $callable
     * @param $weight
     * @return $this
     */
    public function on($name, $callable, $weight = EventInterface::EVENT_LOW);

    /**
     * @param $name
     * @return $this
     */
    public function off($name);

    /**
     * @param $name
     * @param array|null $params
     * @return mixed
     */
    public function trigger($name, array $params = []);
}