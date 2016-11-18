<?php
/**
 *
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

namespace FastD\Event\Exceptions;

/**
 * Class EventNotFoundException
 *
 * @package FastD\Event\Exceptions
 */
class EventNotFoundException extends EventException
{
    /**
     * EventUndefinedException constructor.
     *
     * @param string $eventName
     */
    public function __construct($eventName)
    {
        parent::__construct(sprintf('Event "%s" is not found.', $eventName));
    }
}