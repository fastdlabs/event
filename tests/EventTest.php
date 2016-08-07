<?php
/**
 *
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

use FastD\Event\Event;

class EventTest extends \PHPUnit_Framework_TestCase
{
    public function arrayReturn()
    {
        return ['name' => 'jan'];
    }

    public function testOn()
    {
        $event = new Event();

        $event->on('test.name', function () {
            return 'name';
        });

        $this->assertEquals('name', $event->trigger('test.name'));

        $event->on('test.array', [$this, 'arrayReturn']);

        $this->assertEquals(['name' => 'jan'], $event->trigger('test.array'));
    }

    public function testArgs()
    {
        $event = new Event();

        $event->on('test.args', function ($name) {
            return $name;
        });

        $this->assertEquals('jan', $event->trigger('test.args', ['jan']));
    }

    public function setUp()
    {
        include_once __DIR__ . '/events/StringEvent.php';
        include_once __DIR__ . '/events/ArrayEvent.php';
        include_once __DIR__ . '/events/ArgsEvent.php';
        include_once __DIR__ . '/events/ObjectEvent.php';
        include_once __DIR__ . '/events/objects/Order.php';
        include_once __DIR__ . '/events/HandleEvent.php';
        include_once __DIR__ . '/events/BindToEvent.php';
    }

    public function testStringEventCallable()
    {
        $stringEvent = new StringEvent();

        $stringEvent->on('demoAction', [$stringEvent, 'demoAction']);

        $result = $stringEvent->trigger('demoAction');

        $this->assertEquals('StringEvent::demoAction', $result);
    }

    public function testArrayEventCallable()
    {
        $arrayEvent = new ArrayEvent();

        $arrayEvent->on('arrayAction', [$arrayEvent, 'arrayAction']);

        $result = $arrayEvent->trigger('arrayAction');

        $this->assertEquals([
            'user' => [
                'name' => 'jan',
                'age' => 19
            ]
        ], $result);
    }

    public function testArgsEventCallable()
    {
        $argsEvent = new ArgsEvent();

        $argsEvent->on('argsAction', [$argsEvent, 'argsAction']);

        $result = $argsEvent->trigger('argsAction', [10, 20]);

        $this->assertEquals(30, $result);
    }

    public function testObjectEventCallable()
    {
        $objectEvent = new ObjectEvent();

        $objectEvent->on('orderAction', [$objectEvent, 'orderAction']);

        $result = $objectEvent->trigger('orderAction', [new Order]);

        $this->assertEquals(33.6, $result->getPrice());

        $this->assertInstanceOf(Order::class, $result);
    }

    public function testEventOnHandle()
    {
        $handleEvent = new HandleEvent();

        $result = $handleEvent->trigger('success');

        $this->assertEquals($result, 'event handle on success');
    }

    public function testEventBindTo()
    {
        $bindToEvent = new BindToEvent();

        $bindToEvent->on('test', function () {
            return 'test event';
        }, Event::EVENT_BEFORE, [new Order(), 'bindToReturn']);

        $result = $bindToEvent->trigger('test');

        print_r($result);
    }
}
