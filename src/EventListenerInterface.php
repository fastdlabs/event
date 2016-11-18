<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

namespace FastD\Event;

/**
 * Interface EventListenerInterface
 *
 * @package FastD\Event
 */
interface EventListenerInterface
{
    /**
     * Handle event trigger
     *
     * @param EventInterface $event
     * @param array $arguments
     * @return mixed
     */
    public function handle(EventInterface $event, array $arguments = []);
}