<?php

namespace Lihq1403\ThinkRbac\model;

use think\model\Pivot;

/**
 * 角色-权限 关联表
 * Class RolePermission
 * @package Lihq1403\ThinkRbac\model
 */
class RolePermission extends Pivot
{
    public $name = 'lihq1403_role_permission';

    public $autoWriteTimestamp = 'int';
}