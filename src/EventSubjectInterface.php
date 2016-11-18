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
 * Interface EventSubjectInterface
 *
 * @package FastD\Event
 */
interface EventSubjectInterface
{
    /**
     * @param EventListenerInterface $eventListener
     */
    public function attach(EventListenerInterface $eventListener);

    /**
     * @param EventListenerInterface $eventListener
     */
    public function detach(EventListenerInterface $eventListener);

    /**
     * @param array $arguments
     * @return void
     */
    public function broadcast(array $arguments = []);
}