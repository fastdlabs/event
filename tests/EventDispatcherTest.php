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
        include_once __DIR__ . '/events/HandleEvent.php';
    }

    public function testEventDispatcher()
    {
        $arrayEvent = new ArrayEvent();

        $arrayEvent->on('test', function () {
            return 'array event: test';
        });

        $dispatcher = new EventDispatcher();

        $dispatcher->add($arrayEvent);

        $subject = new Subject();

        $subject->setEventDispatcher($dispatcher);

//        $subject->update();
    }
}
