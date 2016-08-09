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
    const TRIGGER_BEFORE = 'before';
    const TRIGGER_AFTER  = 'after';

    /**
     * @param $name
     * @param $callable
     * @param $when
     * @param $bindTo
     * @return $this
     */
    public function on($name, $callable, $when = EventInterface::TRIGGER_BEFORE, $bindTo = null);

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