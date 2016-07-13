<?php
/**
 *
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

namespace FastD\Event\Tests;

use FastD\Event\Event;

class EventTest extends \PHPUnit_Framework_TestCase
{
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

    public function arrayReturn()
    {
        return ['name' => 'jan'];
    }
}
