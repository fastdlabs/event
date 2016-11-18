<?php

/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */
class EventTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        include_once __DIR__ . '/demo/DemoListener.php';
        include_once __DIR__ . '/demo/DemoEvent.php';
    }

    public function testEvent()
    {
        $event = new \FastD\Event\Event('demo', function () {
            return 'demo event';
        });

        $result = $event->broadcast();

        $this->assertEquals(['demo event'], $result);
    }
}
