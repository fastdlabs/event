<?php
use FastD\Event\EventDispatcher;

/**
 *
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
        include_once __DIR__ . '/objects/Subject.php';
        include_once __DIR__ . '/events/ArrayEvent.php';
    }

    public function testEventDispatcher()
    {
        $arrayEvent = new ArrayEvent();

        $eventDispatcher = new EventDispatcher();

        $eventDispatcher->on('array', $arrayEvent);

        $this->assertEquals([
            'user' => [
                'name' => 'jan',
                'age' => 19
            ]
        ], $eventDispatcher->trigger('array.array'));
    }

    public function testEventOn()
    {
        $eventDispatcher = new EventDispatcher();

        $eventDispatcher->on('test', function () {
            return 'test';
        });

        $this->assertEquals('test', $eventDispatcher->trigger('test'));
    }

    /**
     * @expectedException \FastD\Event\Exceptions\EventUndefinedException
     */
    public function testEventOff()
    {
        $eventDispatcher = new EventDispatcher();

        $eventDispatcher->on('test', function () {
            return 'test';
        });

        $eventDispatcher->off('test');

        $this->assertEquals('test', $eventDispatcher->trigger('test'));
    }

    /**
     * @expectedException \FastD\Event\Exceptions\EventUndefinedException
     */
    public function testEventOffEventObject()
    {
        $arrayEvent = new ArrayEvent();

        $eventDispatcher = new EventDispatcher();

        $eventDispatcher->on('array', $arrayEvent);

        $eventDispatcher->off('array');

        $this->assertEquals([
            'user' => [
                'name' => 'jan',
                'age' => 19
            ]
        ], $eventDispatcher->trigger('array.array'));
    }
}
