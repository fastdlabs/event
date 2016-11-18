<?php

/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */
class DemoListener extends \FastD\Event\EventListener
{
    public function handle(\FastD\Event\EventInterface $event, array $arguments = [])
    {
        echo 'handle ok';
    }
}