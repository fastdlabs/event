<?php
declare(strict_types=1);

use FastD\Event\EventDispatcher;
use FastD\Event\ListenerProvider;
use FastD\Event\EventPriority;

require __DIR__ . '/../vendor/autoload.php';
include __DIR__ . '/UserLoginLogListener.php';
include __DIR__ . '/UserLoginSuccessEvent.php';


// 1. 初始化监听器提供者和事件调度器
$listenerProvider = new ListenerProvider();
$eventDispatcher = new EventDispatcher($listenerProvider);

// 2. 注册「用户登录成功事件」对应的监听器（支持多个监听器及优先级）
// 高优先级监听器先执行
$listenerProvider->addListener(
    UserLoginSuccessEvent::class,
    new UserLoginLogListener(), // 监听器 1：日志记录
    EventPriority::HIGH // 设置高优先级
);
$listenerProvider->addListener(
    UserLoginSuccessEvent::class,
    new UserLoginNoticeListener(), // 监听器 2：通知发送
    EventPriority::LOW // 设置默认优先级
);

// 示例：添加更高优先级的监听器
// $listenerProvider->addListener(
//     UserLoginSuccessEvent::class,
//     function (UserLoginSuccessEvent $event) {
//         echo "【最高优先级监听器】处理登录事件，用户ID：" . $event->getUserId() . "\n";
//     }, // 匿名函数监听器
//     EventPriority::HIGHEST // 最高优先级
// );

// 或者使用数字定义优先级
// $listenerProvider->addListener(
//     UserLoginSuccessEvent::class,
//     function (UserLoginSuccessEvent $event) {
//         echo "【自定义优先级监听器】处理登录事件，用户ID：" . $event->getUserId() . "\n";
//     }, // 匿名函数监听器
//     999 // 自定义优先级值
// );

// 3. 模拟业务流程：用户登录成功，创建事件对象
$userId = 1001;
$loginIp = '127.0.0.1';
$loginEvent = new UserLoginSuccessEvent($userId, $loginIp);

// 4. 调度事件（PSR-14 核心：dispatch 方法）
$processedEvent = $eventDispatcher->dispatch($loginEvent);

// 5. （可选）从处理后的事件中获取数据（若事件有修改）
echo "\n事件处理完成，用户 ID：{$processedEvent->getUserId()}\n";