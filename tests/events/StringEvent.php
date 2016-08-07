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
class StringEvent extends Event
{
    /**
     * @return string
     */
    public function demoAction()
    {
        return __METHOD__;
    }
}