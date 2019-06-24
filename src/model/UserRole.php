<?php

namespace Lihq1403\ThinkRbac\model;

use think\model\Pivot;

/**
 * 管理员-角色 关联表
 * Class UserRole
 * @package Lihq1403\ThinkRbac\model
 */
class UserRole extends Pivot
{
    public $name = 'rbac_user_role';

    public $autoWriteTimestamp = 'int';
}