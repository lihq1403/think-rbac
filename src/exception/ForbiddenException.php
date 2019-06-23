<?php

namespace Lihq1403\ThinkRbac\exception;


use Throwable;

class ForbiddenException extends Exception
{
    public function __construct($message = "无权限", $code = 401, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}