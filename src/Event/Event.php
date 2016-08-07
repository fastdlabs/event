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
class Event implements EventInterface
{
    /**
     * @var array
     */
    protected $events = [];

    /**
     * @param $name
     * @param $callable
     * @param $weight
     * @return $this
     */
    public function on($name, $callable, $weight = EventInterface::EVENT_LOW)
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
     * @param array|null $params
     * @return mixed
     */
    public function trigger($name, array $params = [])
    {
        if (!isset($this->events[$name])) {
            throw new InvalidArgumentException(sprintf('Event "%s" is undefined.', $name));
        }

        if (method_exists($this, $name)) {
            $previous = call_user_func_array([$this, $name], $params);
            if (!is_array($previous)) {
                $previous = [$previous];
            }
            foreach ($previous as $previou) {
                array_unshift($params, $previou);
            }
        }

        $eventReturn = call_user_func_array($this->events[$name], $params);

        return $eventReturn;
    }
}