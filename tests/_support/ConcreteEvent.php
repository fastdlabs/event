<?php

namespace Tests\_support;

class ConcreteEvent implements EventInterface
{
    public function __construct(private string $data)
    {
    }

    public function getData(): string
    {
        return $this->data;
    }
}