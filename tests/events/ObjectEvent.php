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
class ObjectEvent extends Event
{
    public function orderAction(Order $order)
    {
        $order->price *= 2;

        return $order;
    }
}