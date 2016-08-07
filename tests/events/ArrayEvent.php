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
class ArrayEvent extends Event
{
    public function arrayAction()
    {
        return [
            'user' => [
                'name' => 'jan',
                'age' => 19
            ]
        ];
    }
}