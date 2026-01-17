# FastD Event - PSR-14 Event Dispatcher Implementation

FastD Event 是一个轻量级、高性能的 PSR-14 兼容事件调度器实现，提供简单易用的事件驱动架构解决方案。

## 环境要求

- PHP >= 8.2

## 特性

- ✅ 完全符合 [PSR-14 Event Dispatcher 标准](https://www.php-fig.org/psr/psr-14/)
- ⚡ 高性能缓存机制，避免重复计算事件继承层次结构
- 🎯 支持事件监听器优先级控制
- 🔥 支持继承链和接口实现的事件监听
- 🛑 支持可停止事件传播
- 🧪 100% 测试覆盖率

## 安装

使用 Composer 安装：

```bash
composer require fastd/event
```

## 快速开始

### 1. 创建事件类

```php
<?php

class UserLoginSuccessEvent
{
    public function __construct(
        public int $userId,
        public string $ipAddress,
        public string $loginTime
    ) {
    }
}
```

### 2. 创建事件监听器

```php
<?php

class UserLoginLogListener
{
    public function __invoke(UserLoginSuccessEvent $event): void
    {
        echo "【登录日志监听器】已记录登录日志：用户 {$event->userId} 于 {$event->loginTime} 从 IP {$event->ipAddress} 登录成功\n";
    }
}

class UserLoginNotificationListener
{
    public function __invoke(UserLoginSuccessEvent $event): void
    {
        echo "【登录通知监听器】已发送通知：用户 {$event->userId}，您已成功登录系统（登录 IP：{$event->ipAddress}，登录时间：{$event->loginTime}）\n";
    }
}
```

### 3. 配置并使用事件调度器

```php
<?php

require_once 'vendor/autoload.php';

use FastD\Event\EventDispatcher;
use FastD\Event\ListenerProvider;
use FastD\Event\EventPriority;

// 创建监听器提供者
$provider = new ListenerProvider();

// 添加监听器（使用默认优先级）
$provider->addListener(UserLoginSuccessEvent::class, new UserLoginLogListener());

// 添加高优先级监听器
$provider->addListener(UserLoginSuccessEvent::class, new UserLoginNotificationListener(), EventPriority::HIGH);

// 创建事件调度器
$dispatcher = new EventDispatcher($provider);

// 创建并分发事件
$event = new UserLoginSuccessEvent(1001, '127.0.0.1', date('Y-m-d H:i:s'));
$result = $dispatcher->dispatch($event);

echo "\n事件处理完成，用户 ID：{$result->userId}\n";
```

## 高级功能

### 事件优先级

您可以使用内置的优先级常量或自定义数值来控制监听器的执行顺序：

```php
use FastD\Event\EventPriority;

// 使用预定义优先级
$provider->addListener(MyEvent::class, $listener, EventPriority::HIGHEST);  // 最高优先级
$provider->addListener(MyEvent::class, $listener, EventPriority::HIGH);     // 高优先级
$provider->addListener(MyEvent::class, $listener, EventPriority::NORMAL);   // 普通优先级
$provider->addListener(MyEvent::class, $listener, EventPriority::LOW);      // 低优先级
$provider->addListener(MyEvent::class, $listener, EventPriority::LOWEST);   // 最低优先级

// 或使用自定义数值（数值越大优先级越高）
$provider->addListener(MyEvent::class, $listener, 1000);  // 高优先级
$provider->addListener(MyEvent::class, $listener, -100);  // 低优先级
```

### 继承和接口支持

监听器可以监听基类或接口事件，子类事件会自动触发相应的监听器：

```php
interface BaseEventInterface {}

class BaseEvent implements BaseEventInterface {}

class ChildEvent extends BaseEvent {}

// 这个监听器会同时处理 BaseEvent 和 ChildEvent
$provider->addListener(BaseEvent::class, $baseListener);
$provider->addListener(BaseEventInterface::class, $interfaceListener);

// 分发子类事件时，父类和接口的监听器也会被触发
$dispatcher->dispatch(new ChildEvent());
```

### 可停止事件

使用 `StoppableEvent` 类可以让事件在特定条件下停止传播：

```php
use FastD\Event\StoppableEvent;

class MyStoppableEvent extends StoppableEvent
{
    public function __construct(private string $data)
    {
        parent::__construct();
    }

    public function getData(): string
    {
        return $this->data;
    }
}

// 在某个监听器中停止事件传播
class StopEventListener
{
    public function __invoke(MyStoppableEvent $event): void
    {
        if ($event->getData() === 'stop') {
            $event->stopPropagation();
        }
    }
}
```

## 架构组件

### EventDispatcher

实现 `Psr\EventDispatcher\EventDispatcherInterface` 接口，负责调度事件并执行监听器。

### ListenerProvider

实现 `Psr\EventDispatcher\ListenerProviderInterface` 接口，负责管理事件与监听器的映射关系，并支持优先级排序。

### StoppableEvent

实现 `Psr\EventDispatcher\StoppableEventInterface` 接口，提供事件传播停止功能。

### EventPriority

提供预定义的事件优先级常量，方便管理监听器执行顺序。

## 测试

运行单元测试：

```bash
./vendor/bin/phpunit
```

## 许可证

MIT License