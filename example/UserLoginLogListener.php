<?php

declare(strict_types=1);

use FastD\Event\EventListenerInterface;

/**
 * 登录日志监听器（记录用户登录日志）
 */
class UserLoginLogListener implements EventListenerInterface
{
    /**
     * 监听器处理逻辑（__invoke 方法让类可调用）
     * @param UserLoginSuccessEvent $event
     * @return void
     */
    public function __invoke(\Psr\EventDispatcher\StoppableEventInterface $stoppableEvent): void
    {
        $log = sprintf(
            '用户 %d 于 %s 从 IP %s 登录成功; 触发事件: ' . get_class($stoppableEvent),
            $stoppableEvent->getUserId(),
            $stoppableEvent->getLoginTime(),
            $stoppableEvent->getLoginIp()
        );

        echo "【登录日志监听器】已记录登录日志：{$log}\n";

        // （可选）停止事件传播，后续的「登录通知监听器」将不再执行
        // $event->stopPropagation();
    }
}

/**
 * 登录通知监听器（模拟发送登录成功通知）
 */
class UserLoginNoticeListener implements EventListenerInterface
{
    public function __invoke(\Psr\EventDispatcher\StoppableEventInterface $stoppableEvent): void
    {
        $notice = sprintf(
            '用户 %d，您已成功登录系统（登录 IP：%s，登录时间：%s），触发事件：' . get_class($stoppableEvent),
            $stoppableEvent->getUserId(),
            $stoppableEvent->getLoginIp(),
            $stoppableEvent->getLoginTime()
        );

        // 模拟发送通知（实际场景可发送短信/邮件/站内信）
        echo "【登录通知监听器】已发送通知：{$notice}\n";
    }
}