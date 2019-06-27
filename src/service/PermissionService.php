<?php

namespace Lihq1403\ThinkRbac\service;

use Lihq1403\ThinkRbac\model\Permission;
use Lihq1403\ThinkRbac\protected_traits\Singleton;

class PermissionService
{
    use Singleton;

    /**
     * 重置权限分组
     * @param array $permission_group_id
     * @return bool
     */
    public function resetPermissionGroup(array $permission_group_id)
    {
        $model = new Permission();
        $model->whereIn('permission_group_id', $permission_group_id)->data(['permission_group_id' => 0])->update();
        return true;
    }

}