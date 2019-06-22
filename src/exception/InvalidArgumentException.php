<?php

namespace  Lihq1403\ThinkRbac\exception;


use Throwable;

class InvalidArgumentException extends Exception
{
    public function __construct($message = "无效的参数", $code = 400, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}