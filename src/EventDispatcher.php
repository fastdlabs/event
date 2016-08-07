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

class EventDispatcher
{
    protected $events = [];

    public function add(Event $event)
    {
        $this->events[] = $event;

        return $this;
    }

    public function trigger($name, $event, array $params = [])
    {
        return $this->events[$name]->trigger($event, $params);
    }
}