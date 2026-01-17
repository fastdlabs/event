<?php

namespace Tests\_support;

class ChildEvent extends BaseEvent
{
    public function __construct(string $data)
    {
        parent::__construct($data);
    }
}