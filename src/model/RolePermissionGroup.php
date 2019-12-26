<?php

namespace Lihq1403\ThinkRbac\model;

use think\model\Pivot;

/**
 * 角色-权限组 关联表
 * Class RolePermission
 * @package Lihq1403\ThinkRbac\model
 */
class RolePermissionGroup extends Pivot
{
    public $name = 'rbac_role_permission_group';

    public $autoWriteTimestamp = true;

    protected $hidden = [
        'delete_time'
    ];
}