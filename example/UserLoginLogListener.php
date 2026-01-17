<?php

declare(strict_types=1);


/**
 * 登录日志监听器（记录用户登录日志）
 */
class UserLoginLogListener
{
    /**
     * 监听器处理逻辑（__invoke 方法让类可调用）
     * @param UserLoginSuccessEvent $event
     * @return void
     */
    public function __invoke(UserLoginSuccessEvent $event): void
    {
        $log = sprintf(
            '用户 %d 于 %s 从 IP %s 登录成功',
            $event->getUserId(),
            $event->getLoginTime(),
            $event->getLoginIp()
        );

        // 模拟写入日志（实际场景可写入文件/数据库）
        $file = __DIR__ . '/storage/logs/login.log';
        if (!file_exists($file)) {
            mkdir(dirname($file) . '/storage/logs', 0777, true);
        }
        file_put_contents($file, $log . PHP_EOL, FILE_APPEND);

        echo "【登录日志监听器】已记录登录日志：{$log}\n";

        // （可选）停止事件传播，后续的「登录通知监听器」将不再执行
        // $event->stopPropagation();
    }
}

/**
 * 登录通知监听器（模拟发送登录成功通知）
 */
class UserLoginNoticeListener
{
    public function __invoke(UserLoginSuccessEvent $event): void
    {
        $notice = sprintf(
            '用户 %d，您已成功登录系统（登录 IP：%s，登录时间：%s）',
            $event->getUserId(),
            $event->getLoginIp(),
            $event->getLoginTime()
        );

        // 模拟发送通知（实际场景可发送短信/邮件/站内信）
        echo "【登录通知监听器】已发送通知：{$notice}\n";
    }
}