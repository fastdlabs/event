<?php

use FastD\Event\EventListenerInterface;

/**
 * 实现 EventListenerInterface 的安全检查监听器
 */
class UserLoginSecurityCheckListener implements EventListenerInterface
{
    public function handle(object $event): void
    {
        if ($event instanceof UserLoginSuccessEvent) {
            echo "[Security Check] Validating login for user ID: {$event->getUserId()} from IP: {$event->getLoginIp()}\n";
            
            // 执行安全检查逻辑
            $this->performSecurityCheck($event);
        }
    }
    
    public function __invoke(object $event): void
    {
        $this->handle($event);
    }
    
    private function performSecurityCheck(UserLoginSuccessEvent $event): void
    {
        // 模拟安全检查逻辑
        echo "[Security Check] Security validation passed for user {$event->getUserId()}\n";
    }
}