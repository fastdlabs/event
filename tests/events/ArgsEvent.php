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
class ArgsEvent extends Event
{
    public function argsAction($num, $step = 2)
    {
        return $num + $step;
    }
}