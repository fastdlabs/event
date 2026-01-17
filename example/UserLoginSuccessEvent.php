<?php
declare(strict_types=1);


use FastD\Event\StoppableEvent;

/**
 * 用户登录成功事件（具体业务事件）
 */
class UserLoginSuccessEvent extends StoppableEvent
{
    /**
     * 事件上下文数据
     * @var int
     */
    private int $userId;

    /**
     * @var string
     */
    private string $loginIp;

    /**
     * @var string
     */
    private string $loginTime;

    /**
     * 构造函数初始化上下文数据
     * @param int $userId
     * @param string $loginIp
     */
    public function __construct(int $userId, string $loginIp)
    {
        parent::__construct(); // 调用父类构造函数
        $this->userId = $userId;
        $this->loginIp = $loginIp;
        $this->loginTime = date('Y-m-d H:i:s');
    }

    // 提供 Getter 方法，供监听器获取事件数据
    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getLoginIp(): string
    {
        return $this->loginIp;
    }

    public function getLoginTime(): string
    {
        return $this->loginTime;
    }
}