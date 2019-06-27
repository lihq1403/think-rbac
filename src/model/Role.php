<?php

namespace Lihq1403\ThinkRbac\model;


use Lihq1403\ThinkRbac\helper\PageHelper;

/**
 * 角色表
 * Class Role
 * @package Lihq1403\ThinkRbac\model
 */
class Role extends BaseModel
{
    public $name = 'rbac_role';

    /**
     * @return \think\model\relation\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(config('rbac.user_model'));
    }

    /**
     * @return \think\model\relation\BelongsToMany
     */
    public function permissions()
    {
        return $this->belongsToMany(Permission::class, '\\Lihq1403\\ThinkRbac\\model\\RolePermissionGroup', 'permission_id', 'role_id');
    }

    /**
     * 保存角色
     * @param array $data
     * @return $this
     */
    public function saveRole($data = [])
    {
        if (!empty($data)) {
            $this->data($data);
        }
        if (!empty($data['id'])) {
            $this->isUpdate(true);
        }
        $this->allowField(true)->save();
        return $this;
    }

    /**
     * 删除角色
     * @param $role_id
     * @return bool
     * @throws \Exception
     */
    public function delRole($role_id)
    {
        if (!is_array($role_id)) {
            $role_id = [$role_id];
        }
        // 用户-角色 关联数据删除
        UserRole::whereIn('role_id', $role_id)->delete();
        // 角色-权限组 关联数据删除
        RolePermissionGroup::whereIn('role_id', $role_id)->delete();
        return self::destroy($role_id);
    }

    /**
     * 获取所有角色分页列表
     * @param $map
     * @param $field
     * @param int $page
     * @param int $page_rows
     * @return array
     */
    public function getList($map, $field, $page = 1, $page_rows = 10)
    {
        return (new PageHelper(new self()))->where($map)->setFields($field)->page($page)->pageRows($page_rows)->result();
    }

    /**
     * 角色分配权限
     * @param array $permissions_id
     * @return array|bool|\think\model\Pivot
     */
    public function assignPermission(array $permissions_id)
    {
        // 检查角色是否已经存在该权限
        $has_permissions_id = array_column($this->permissions->toArray(), 'id');

        // 剔除已存在的权限id
        $permissions_id = array_diff($permissions_id, $has_permissions_id);
        if (empty($permissions_id)) {
            return true;
        }

        // 当前可选权限id
        $allowed_permissions_id = Permission::whereIn('id', $permissions_id)->column('id');
        if (empty($allowed_permissions_id)) {
            return true;
        }

        // 关联添加
        return $this->permissions()->save($allowed_permissions_id);
    }

    /**
     * 取消分配的权限
     * @param array $permissions_id
     * @return int
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function cancelPermission(array $permissions_id)
    {
        return RolePermissionGroup::where('role_id', $this->id)->whereIn('permission_id', $permissions_id)->delete();
    }
}