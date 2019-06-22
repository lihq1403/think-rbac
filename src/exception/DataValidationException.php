<?php

namespace Lihq1403\ThinkRbac\exception;


use Throwable;

/**
 * 数据验证错误
 * Class DataValidationException
 * @package Lihq1403\ThinkRbac\exception
 */
class DataValidationException extends Exception
{
    public function __construct($message = "数据验证错误", $code = 422, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}