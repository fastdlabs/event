<?php
use FastD\Event\Event;

/**
 *
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */
class HandleEvent extends Event
{
    public function onSuccess()
    {
        return 'event handle on success';
    }
}