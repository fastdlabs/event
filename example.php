<?php

declare(strict_types=1);

require_once __DIR__ . '/vendor/autoload.php';

use FastD\Event\Event;
use FastD\Event\EventListener;
use FastD\Event\ListenerProvider;
use FastD\Event\EventDispatcher;
use Psr\EventDispatcher\StoppableEventInterface;

/**
 * 示例事件类
 */
class UserRegisteredEvent extends Event
{
    public function __construct(private string $username, private string $email)
    {
        // 不需要调用父类构造函数，因为Event类的构造函数是空的
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getEmail(): string
    {
        return $this->email;
    }
}

/**
 * 用户注册事件监听器 - 发送欢迎邮件
 */
class SendWelcomeEmailListener extends EventListener
{
    public function listen(): iterable
    {
        return [UserRegisteredEvent::class];
    }

    public function process(object $event): void
    {
        if ($event instanceof UserRegisteredEvent) {
            echo "[Listener 1] 发送欢迎邮件给用户: {$event->getUsername()} ({$event->getEmail()})\n";
        }
    }

    public function handle(object $event): void
    {
        $this->process($event);
    }

    public function priority(): int
    {
        return 10; // 高优先级
    }
}

/**
 * 用户注册事件监听器 - 更新统计数据
 */
class UpdateStatisticsListener extends EventListener
{
    public function listen(): iterable
    {
        return [UserRegisteredEvent::class];
    }

    public function process(object $event): void
    {
        if ($event instanceof UserRegisteredEvent) {
            echo "[Listener 2] 更新注册统计: {$event->getUsername()}\n";
        }
    }

    public function handle(object $event): void
    {
        $this->process($event);
    }

    public function priority(): int
    {
        return 5; // 中等优先级
    }
}

/**
 * 用户注册事件监听器 - 异常示例
 */
class ErrorHandlingListener extends EventListener
{
    public function listen(): iterable
    {
        return [UserRegisteredEvent::class];
    }

    public function process(object $event): void
    {
        if ($event instanceof UserRegisteredEvent) {
            // 模拟一个异常情况
            throw new \Exception("处理用户注册时发生错误: {$event->getUsername()}");
        }
    }

    public function handle(object $event): void
    {
        $this->process($event);
    }

    public function priority(): int
    {
        return 1; // 低优先级
    }
}

/**
 * 自定义事件，支持停止传播
 */
class CustomStoppableEvent extends Event implements StoppableEventInterface
{
    public function stopPropagation(): void
    {
        parent::stopPropagation();
    }
}

echo "=== FastD Event 示例 ===\n\n";

// 1. 基本事件分发示例
echo "1. 基本事件分发示例:\n";
$provider = new ListenerProvider();
$dispatcher = new EventDispatcher($provider);

$userRegisteredEvent = new UserRegisteredEvent('john_doe', 'john@example.com');

$sendEmailListener = new SendWelcomeEmailListener();
$updateStatsListener = new UpdateStatisticsListener();

$provider->addListener($sendEmailListener, $updateStatsListener);

$resultEvent = $dispatcher->dispatch($userRegisteredEvent);

echo "\n";

// 2. 事件传播停止示例
echo "2. 事件传播停止示例:\n";
$stoppableEvent = new CustomStoppableEvent();
$stoppableEvent->stopPropagation(); // 停止传播

$customResult = $dispatcher->dispatch($stoppableEvent);
echo "事件传播已停止，监听器不会被调用\n\n";

// 3. 异常处理示例
echo "3. 异常处理示例:\n";
$errorProvider = new ListenerProvider();
$errorDispatcher = new EventDispatcher($errorProvider);

$errorProvider->addListener(
    new SendWelcomeEmailListener(),
    new ErrorHandlingListener(),  // 这个监听器会抛出异常
    new UpdateStatisticsListener()
);

$userWithErrorEvent = new UserRegisteredEvent('jane_doe', 'jane@example.com');
$resultWithException = $errorDispatcher->dispatch($userWithErrorEvent);

// 检查是否有异常被捕获
if (!empty($resultWithException->getThrowables())) {
    echo "捕获到异常:\n";
    foreach ($resultWithException->getThrowables() as $listenerClass => $throwable) {
        echo "  - {$listenerClass}: {$throwable->getMessage()}\n";
    }
} else {
    echo "没有捕获到异常\n";
}

echo "\n";

// 4. 监听器优先级示例
echo "4. 监听器优先级示例:\n";
$priorityProvider = new ListenerProvider();
$priorityDispatcher = new EventDispatcher($priorityProvider);

$priorityProvider->addListener(
    new UpdateStatisticsListener(), // 优先级 5
    new SendWelcomeEmailListener()  // 优先级 10
);

$prioritizedEvent = new UserRegisteredEvent('priority_user', 'priority@example.com');
$priorityDispatcher->dispatch($prioritizedEvent);

echo "\n=== 示例结束 ===\n";

// 5. 新增时间中断事件监听器示例

echo "5. 时间中断事件监听器示例:\n";

/**
 * 时间中断事件类
 */
class TimeInterruptEvent extends Event
{
    public function __construct(private string $interruptTime, private string $reason)
    {
    }

    public function getInterruptTime(): string
    {
        return $this->interruptTime;
    }

    public function getReason(): string
    {
        return $this->reason;
    }
}

/**
 * 时间中断事件监听器 - 处理中断事件
 */
class TimeInterruptHandlerListener extends EventListener
{
    public function listen(): iterable
    {
        return [TimeInterruptEvent::class];
    }

    public function process(object $event): void
    {
        if ($event instanceof TimeInterruptEvent) {
            echo "[Time Interrupt Handler] 时间中断事件触发: {$event->getInterruptTime()}, 原因: {$event->getReason()}\n";
            
            // 模拟一些处理逻辑
            sleep(1); // 模拟处理时间
            echo "[Time Interrupt Handler] 时间中断事件处理完成\n";
            $event->stopPropagation();
        }
    }

    public function handle(object $event): void
    {
        $this->process($event);
    }

    public function priority(): int
    {
        return 8; // 较高优先级
    }
}

/**
 * 时间中断事件监听器 - 日志记录
 */
class TimeInterruptLoggerListener extends EventListener
{
    public function listen(): iterable
    {
        return [TimeInterruptEvent::class];
    }

    public function process(object $event): void
    {
        if ($event instanceof TimeInterruptEvent) {
            echo "[Time Interrupt Logger] 记录时间中断事件: {$event->getInterruptTime()}, {$event->getReason()}\n";
        }
    }

    public function handle(object $event): void
    {
        $this->process($event);
    }

    public function priority(): int
    {
        return 3; // 较低优先级
    }
}

// 创建新的事件分发器用于时间中断示例
$timeInterruptProvider = new ListenerProvider();
$timeInterruptDispatcher = new EventDispatcher($timeInterruptProvider);

// 添加时间中断相关的监听器
$timeInterruptProvider->addListener(
    new TimeInterruptHandlerListener(),
    new TimeInterruptLoggerListener()
);

// 创建并触发时间中断事件
$timeInterruptEvent = new TimeInterruptEvent('2026-01-22 10:30:00', '系统维护');
$result = $timeInterruptDispatcher->dispatch($timeInterruptEvent);

echo "\n时间中断事件示例执行完毕\n\n";