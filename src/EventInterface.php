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
    /**
     * @param $name
     * @param $callable
     * @return $this
     */
    public function on($name, $callable);

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