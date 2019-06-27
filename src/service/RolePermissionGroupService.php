<?php

namespace Lihq1403\ThinkRbac\service;

use Lihq1403\ThinkRbac\model\RolePermissionGroup;
use Lihq1403\ThinkRbac\protected_traits\Singleton;

class RolePermissionGroupService
{
    use Singleton;

    /**
     * 删除相关 角色-权限组 记录
     * @param array $permission_group_id
     * @return bool
     */
    public function delByPermissionGroupId(array $permission_group_id)
    {
        RolePermissionGroup::destroy(function ($query) use ($permission_group_id) {
            $query->whereIn('permission_group_id', $permission_group_id);
        });
        return true;
    }
}