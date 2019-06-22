<?php

namespace Lihq1403\ThinkRbac\model;

use think\model\Pivot;

/**
 * 管理员-角色 关联表
 * Class AdminUserRole
 * @package Lihq1403\ThinkRbac\model
 */
class AdminUserRole extends Pivot
{
    public $name = 'lihq1403_admin_user_role';

    public $autoWriteTimestamp = 'int';
}