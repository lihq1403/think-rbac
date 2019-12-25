<?php

namespace Lihq1403\ThinkRbac\facade;


use think\Facade;

/**
 * Class RBAC
 * @package Lihq1403\ThinkRbac\facade
 * @see \Lihq1403\ThinkRbac\Rbac
 * @mixin \Lihq1403\ThinkRbac\Rbac
 */
class RBAC extends Facade
{
    protected static function getFacadeClass()
    {
        return 'Lihq1403\ThinkRbac\Rbac';
    }
}