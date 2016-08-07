# Event

事件与调度(暂时没有太多头绪).

## ＃环境要求

* PHP >= 5.6

## Usage

```php
$event = new Event();

$event->on('test.name', function () {
    return 'name';
});

$event->trigger('test.name'); // name
```

### 带参数

```php
$event = new Event();

$event->on('test.args', function ($name) {
    return $name;
});

$event->trigger('test.args', ['jan']); // jan
```

# License MIT
