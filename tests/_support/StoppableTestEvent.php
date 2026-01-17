<?php

namespace Tests\_support;

use FastD\Event\StoppableEvent;

class StoppableTestEvent extends StoppableEvent
{
    private string $data;

    public function __construct(string $data)
    {
        parent::__construct(); // 调用父类构造函数
        $this->data = $data;
    }

    public function getData(): string
    {
        return $this->data;
    }
}