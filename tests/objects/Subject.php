<?php

use FastD\Event\EventAwareTrait;

/**
 *
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */
class Subject
{
    use EventAwareTrait;

    public function update()
    {
        return $this->getEventDispatcher()->trigger($this, 'update');
    }
}