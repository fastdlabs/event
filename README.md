# Event

Simple Event and EventManager

### Requirements

* PHP >= 5.6

### Installation

`composer require "fastd/event:1.0.x-dev" -vvv`

### Usage Event

```php
$event = new \FastD\Event\Event('demo', function () {
    return 'demo event';
});

$result = $event->broadcast();

$this->assertEquals(['demo event'], $result);
```

### Usage EventDispatch

```php
$eventDispatch = new \FastD\Event\EventDispatcher();

$eventDispatch->on('demo', new DemoListener());

echo $eventDispatch->trigger('demo');

$this->expectOutputString('handle ok');
```

### PHPUnit

```
phpunit
```

# License MIT
