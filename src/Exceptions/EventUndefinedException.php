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

use Exception;

/**
 * Class EventUndefinedException
 *
 * @package FastD\Event
 */
class EventUndefinedException extends Exception
{
    /**
     * EventUndefinedException constructor.
     *
     * @param string $eventName
     */
    public function __construct($eventName)
    {
        parent::__construct(sprintf('Event "%s" is undefined.', $eventName));
    }
}