<?php

/**
 *
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */
class Order
{
    public $order = '123';

    public $price = 16.8;

    public function getOrderId()
    {
        return $this->order;
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function bindToReturn($event)
    {

    }
}