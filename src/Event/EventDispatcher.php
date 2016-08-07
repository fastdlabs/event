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
 * Class Event
 *
 * @package FastD\Event
 */
class EventDispatcher
{
    protected $events = [];

    public function addEvent(EventInterface $event)
    {
        $this->events[] = $event;
    }
}