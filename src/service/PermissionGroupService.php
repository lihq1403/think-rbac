<?php

namespace Lihq1403\ThinkRbac\service;

use Lihq1403\ThinkRbac\model\PermissionGroup;
use Lihq1403\ThinkRbac\protected_traits\Singleton;

class PermissionGroupService
{
    use Singleton;

    /**
     * 通过code查询id
     * @param $code
     * @return int|mixed
     */
    public function findIdByCode($code)
    {
        return PermissionGroup::where('code', $code)->value('id') ?? 0;
    }

    /**
     * 查找数据集
     * @param array $code
     * @return array
     */
    public function findIdsByCodes(array $code)
    {
        return PermissionGroup::whereIn('code', $code)->column('id') ?? [];
    }

    /**
     * 所有数据
     * @return array
     */
    public function findAllIds()
    {
        return PermissionGroup::column('id') ?? [];
    }

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