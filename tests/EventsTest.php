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

class EventsTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        include_once __DIR__ . '/events/StringEvent.php';
        include_once __DIR__ . '/events/ArrayEvent.php';
        include_once __DIR__ . '/events/ArgsEvent.php';
        include_once __DIR__ . '/events/ObjectEvent.php';
        include_once __DIR__ . '/events/objects/Order.php';
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

    public function arrayReturn()
    {
        return ['name' => 'jan'];
    }

    public function testStringEventCallable()
    {
        $stringEvent = new StringEvent();

        $stringEvent->on('demoAction', function ($method) {
            return $method;
        });

        $result = $stringEvent->trigger('demoAction');

        $this->assertEquals('StringEvent::demoAction', $result);
    }

    public function testArrayEventCallable()
    {
        $arrayEvent = new ArrayEvent();

        $arrayEvent->on('arrayAction', function ($user) {
            return $user;
        });

        $result = $arrayEvent->trigger('arrayAction');

        $this->assertEquals([
            'user' => [
                'name' => 'jan',
                'age' => 19
            ]
        ], $result);
    }

    public function testArgEventCallable()
    {
        $argsEvent = new ArgsEvent();

        $argsEvent->on('argsAction', function ($num) {
            return $num * 2;
        });

        $result = $argsEvent->trigger('argsAction', [10, 20]);

        $this->assertEquals(60, $result);
    }

    public function testObjectEventCallable()
    {
        $objectEvent = new ObjectEvent();

        $objectEvent->on('orderAction', function (Order $order) {
            return $order;
        });

        $result = $objectEvent->trigger('orderAction', [new Order]);

        $this->assertEquals(33.6, $result->getPrice());

        $this->assertInstanceOf(Order::class, $result);
    }
}
