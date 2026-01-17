<?php

namespace Tests\_support;

use FastD\Event\StoppableEvent;

class StoppableTestEvent extends StoppableEvent
{
    private string $data;

    public function __construct(string $data, bool $propagationStopped = false)
    {
        $this->propagationStopped = $propagationStopped; // 设置父类属性
        $this->data = $data;
    }

    public function getData(): string
    {
        return $this->data;
    }
}