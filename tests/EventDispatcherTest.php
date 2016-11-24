<?php

/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */
class EventDispatcherTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        include_once __DIR__ . '/demo/DemoListener.php';
        include_once __DIR__ . '/demo/DemoEvent.php';
    }

    public function testDispatch()
    {
        $eventDispatch = new \FastD\Event\EventDispatcher();

        $eventDispatch->on('demo', [new DemoListener(), 'handle']);

        echo $eventDispatch->trigger('demo');

        $this->expectOutputString('handle ok');
    }
}
