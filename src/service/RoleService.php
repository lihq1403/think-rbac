<?php

namespace Lihq1403\ThinkRbac\service;


use Lihq1403\ThinkRbac\model\PermissionGroup;
use Lihq1403\ThinkRbac\model\Role;
use Lihq1403\ThinkRbac\model\RolePermissionGroup;
use Lihq1403\ThinkRbac\protected_traits\Singleton;

class RoleService
{
    use Singleton;

    /**
     * 保存数据
     * @param $data
     * @return Role
     */
    public function saveData($data)
    {
        $model = new Role();
        return $model->saveData($data);
    }

    /**
     * 禁用角色
     * @param array $ids
     * @return bool
     */
    public function close(array $ids)
    {
        $model = new Role();
        $model->whereIn('id', $ids)->update(['status' => 0]);
        return true;
    }

    /**
     * 获取角色的权限组列表
     * @param $role_id
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function roleHoldPermissionGroup($role_id)
    {
        // 先获取所有权限组
        $all_group = PermissionGroup::field(['id', 'name', 'description', 'code'])->select()->toArray();

        // 再获取角色目前拥有的权限组id
        if (is_array($role_id)) {
            $has_permission_group_id = RolePermissionGroup::whereIn('role_id', $role_id)->column('permission_group_id');
            if (!empty($has_permission_group_id)) {
                $has_permission_group_id = array_unique($has_permission_group_id);
            }
        } else {
            $has_permission_group_id = RolePermissionGroup::where('role_id', $role_id)->column('permission_group_id');
        }

        foreach ($all_group as &$g) {
            if ($role_id === 0) {
                // 超级管理员
                $g['hold'] = 1;
                continue;
            }
            if (in_array($g['id'], $has_permission_group_id)) {
                $g['hold'] = 1;
            } else {
                $g['hold'] = 0;
            }
        }

        return $all_group;
    }
}