<?php

namespace Lihq1403\ThinkRbac\traits;

use Lihq1403\ThinkRbac\model\UserRole;
use Lihq1403\ThinkRbac\model\Role;

/**
 * 在用户表进行 use RbacUser;
 * Trait RbacUser
 * @package Lihq1403\ThinkRbac\traits
 */
trait RbacUser
{
    /**
     * 用户关联的角色
     * @return \think\model\relation\BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, '\\Lihq1403\\ThinkRbac\\model\\UserRole', 'role_id', 'user_id');
    }

    /**
     * 获取用户的所有角色
     * 重新封装数据
     * @return array
     */
    public function allRoles()
    {
        $roles = $this->roles;

        if (empty($roles)) {
            return [];
        }
        $data = [];
        foreach ($roles as $role) {
            $data[] = [
                'role_id' => $role->id,
                'name' => $role['name'],
                'description' => $role['description'],
            ];
        }
        return $data;
    }

    /**
     * 用户赋予角色
     * @param array $roles
     * @return array|bool|\think\model\Pivot
     */
    public function assignRoles(array $roles)
    {
        // 检查用户是否已经存在该角色
        $has_roles_id = array_column($this->roles->toArray(), 'id');

        // 剔除已存在的权限id
        $roles_id = array_diff($roles, $has_roles_id);
        if (empty($roles_id)) {
            return true;
        }

        // 当前可选角色id
        $allowed_roles_id = Role::whereIn('id', $roles_id)->column('id');
        if (empty($allowed_roles_id)) {
            return true;
        }

        // 关联添加
        return $this->roles()->save($allowed_roles_id);
    }

    /**
     * 取消用户授权角色
     * @param array $roles
     * @return int
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function cancelRoles(array $roles)
    {
        return UserRole::where('user_id', $this->id)->whereIn('role_id', $roles)->delete();
    }

    /**
     * 同步角色
     * @param array $roles
     * @return bool
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function diffRoles(array $roles)
    {
        // 检查用户已经存在的角色
        $has_roles_id = array_column($this->roles->toArray(), 'id');

        // 筛选需要删除，还是新增的权限组
        $del = array_diff($has_roles_id, $roles);
        $add = array_diff($roles, $has_roles_id);

        if (!empty($del)) {
            $del = array_values($del);
            UserRole::where('user_id', $this->id)->whereIn('role_id', $del)->delete();
        }

        if (!empty($add)) {
            $add = array_values($add);
            // 关联添加
            $this->roles()->save($add);
        }

        return true;
    }
}