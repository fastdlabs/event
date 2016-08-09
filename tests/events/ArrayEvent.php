<?php
use FastD\Event\Event;
use FastD\Event\EventInterface;

/**
 *
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */
class ArrayEvent implements EventInterface
{
    public function onArray()
    {
        return [
            'user' => [
                'name' => 'jan',
                'age' => 19
            ]
        ];
    }
}