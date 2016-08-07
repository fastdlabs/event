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
 * Class EventAware
 *
 * @package FastD\Event
 */
trait EventAware
{
    /**
     * @var EventDispatcher $eventDispatcher
     */
    protected $eventDispatcher;

    /**
     * @param EventDispatcher $eventDispatcher
     * @return $this
     */
    public function setEventDispatcher(EventDispatcher $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;

        return $this;
    }

    /**
     * @return EventDispatcher
     */
    public function getEventDispatcher()
    {
        return $this->eventDispatcher;
    }
}