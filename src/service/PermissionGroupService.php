<?php

namespace Lihq1403\ThinkRbac\service;

use Lihq1403\ThinkRbac\model\PermissionGroup;
use Lihq1403\ThinkRbac\protected_traits\Singleton;

class PermissionGroupService
{
    use Singleton;

    /**
     * 保存数据
     * @param array $data
     * @return PermissionGroup
     */
    public function saveData(array $data)
    {
        $model = new PermissionGroup();
        return $model->saveData($data);
    }

    /**
     * 删除权限组
     * @param array $permission_group_id
     * @return bool
     * @throws \Exception
     */
    public function del(array $permission_group_id)
    {
        $model = new PermissionGroup();
        $model->destroy($permission_group_id);

        // 删除相对应的角色-权限组关联
        RolePermissionGroupService::instance()->delByPermissionGroupId($permission_group_id);

        //重置相关权限的权限组归类
        PermissionService::instance()->resetPermissionGroup($permission_group_id);

        return true;
    }

}