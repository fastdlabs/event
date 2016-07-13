# Event
FastD Event.

## ＃环境要求

* PHP 7+

## Usage

```php
$event = new Event();

$event->on('test.name', function () {
    return 'name';
});

$event->trigger('test.name'); // name
```

## todo

* 事件循环(Event Loop, Swoole)
* 异步 (Swoole)

# License MIT
