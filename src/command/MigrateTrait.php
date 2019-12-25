<?php

namespace Lihq1403\ThinkRbac\command;

use \think\facade\Db;

trait MigrateTrait
{
    protected function getPath()
    {
        return __DIR__ . '/../database/migrations';
    }
}