<?php

namespace Lihq1403\ThinkRbac\protected_traits;

/**
 * 启用单例
 * Trait Singleton
 * @package app\common\traits
 */
trait Singleton
{
    private static $instance;

    public static function instance()
    {
        if(!isset(self::$instance)){
            self::$instance = new static();
        }
        return self::$instance;
    }

    private function __construct()
    {
    }

    private function __clone()
    {
    }
}