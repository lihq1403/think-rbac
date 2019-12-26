<?php

namespace Lihq1403\ThinkRbac\lib;

use Lihq1403\ThinkRbac\facade\RBAC;
use Lihq1403\ThinkRbac\protected_traits\Singleton;
use think\Model;

class RBACLib
{
    use Singleton;

    /**
     * 角色新增
     * @param string $name
     * @param string $description
     * @return \Lihq1403\ThinkRbac\model\Role
     * @throws \Lihq1403\ThinkRbac\exception\DataValidationException
     */
    public function addRole(string $name, string $description = '')
    {
        return RBAC::addRole($name, $description);
    }

    /**
     * 角色编辑
     * @param int $role_id
     * @param array $data
     * @return \Lihq1403\ThinkRbac\model\Role
     * @throws \Lihq1403\ThinkRbac\exception\DataValidationException
     * @throws \Lihq1403\ThinkRbac\exception\InvalidArgumentException
     */
    public function editRole(int $role_id, array $data)
    {
        return RBAC::editRole($role_id, $data);
    }

    /**
     * 角色删除
     * @param $role_id
     * @return bool
     * @throws \Lihq1403\ThinkRbac\exception\InvalidArgumentException
     */
    public function delRole($role_id)
    {
        return RBAC::delRole($role_id);
    }

    /**
     * 角色列表
     * @param int $page
     * @param int $page_rows
     * @return array
     */
    public function getRoles(int $page, int $page_rows)
    {
        $map = [];
        $field = [];
        $order = 'id desc';
        return RBAC::getRoles($map, $field, $order, $page, $page_rows);
    }

    /**
     * 权限组新增
     * @param string $name
     * @param string $code
     * @param string $description
     * @return \Lihq1403\ThinkRbac\model\PermissionGroup
     * @throws \Lihq1403\ThinkRbac\exception\DataValidationException
     */
    public function addPermissionGroup(string $name, string $code, string $description = '')
    {
        return RBAC::addPermissionGroup($name, $code, $description);
    }

    /**
     * 权限组编辑
     * @param int $permission_group_id
     * @param array $data
     * @return \Lihq1403\ThinkRbac\model\PermissionGroup
     * @throws \Lihq1403\ThinkRbac\exception\DataValidationException
     * @throws \Lihq1403\ThinkRbac\exception\InvalidArgumentException
     */
    public function editPermissionGroup(int $permission_group_id, array $data)
    {
        return RBAC::editPermissionGroup($permission_group_id, $data);
    }

    /**
     * 权限组列表
     * @param int $page
     * @param int $page_rows
     * @return array
     */
    public function getPermissionGroups(int $page, int $page_rows)
    {
        $map = [];
        $field = [];
        $order = 'id desc';
        return RBAC::getPermissionGroups($map, $field, $order, $page, $page_rows);
    }

    /**
     * 权限组删除
     * @param $permission_group_id
     * @return bool
     * @throws \Exception
     */
    public function delPermissionGroup($permission_group_id)
    {
        return RBAC::delPermissionGroup($permission_group_id);
    }

    /**
     * 权限添加
     * @param string $name
     * @param string $controller
     * @param string $action
     * @param string $description
     * @param string $permission_group_code
     * @param string $behavior
     * @param string $module
     * @return \Lihq1403\ThinkRbac\model\Permission
     * @throws \Lihq1403\ThinkRbac\exception\DataValidationException
     */
    public function addPermission(string $name, string $controller, string $action, string $description, string $permission_group_code, string $behavior, $module = 'admin')
    {
        return RBAC::addPermission($name, $controller, $action, $description, $permission_group_code, $behavior, $module);
    }

    /**
     * 权限编辑
     * @param int $permission_id
     * @param array $data
     * @return \Lihq1403\ThinkRbac\model\Permission
     * @throws \Lihq1403\ThinkRbac\exception\DataValidationException
     * @throws \Lihq1403\ThinkRbac\exception\InvalidArgumentException
     */
    public function editPermission(int $permission_id, array $data)
    {
        return RBAC::editPermission($permission_id, $data);
    }

    /**
     * 权限删除
     * @param $permission_id
     * @return bool
     * @throws \Lihq1403\ThinkRbac\exception\InvalidArgumentException
     */
    public function delPermission($permission_id)
    {
        return RBAC::delPermission($permission_id);
    }

    /**
     * 权限列表
     * @param int $page
     * @param int $page_rows
     * @return array
     */
    public function getPermissions(int $page, int $page_rows)
    {
        $map = [];
        $field = [];
        $order = 'id desc';
        return RBAC::getPermissions($map, $field, $order, $page, $page_rows);
    }

    /**
     * 获取角色的权限组列表
     * @param int $role_id
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function roleHoldPermissionGroup(int $role_id)
    {
        return RBAC::roleHoldPermissionGroup($role_id);
    }

    /**
     * 角色更换权限组
     * @param int $role_id
     * @param $group_code
     * @return bool
     * @throws \Exception
     */
    public function diffPermissionGroup(int $role_id, $group_code)
    {
        return RBAC::diffPermissionGroup($role_id, $group_code);
    }

    /**
     * 获取日志
     * @param int $page
     * @param int $page_rows
     * @return array
     */
    public function getLog(int $page, int $page_rows)
    {
        return RBAC::getLogList($page, $page_rows, []);
    }

    /**
     * 给 用户 分配角色
     * @param Model $userModel
     * @param array $role_id
     * @return bool
     */
    public function assignRoles(Model $userModel, array $role_id)
    {
        $userModel->assignRoles($role_id);
        return true;
    }

    /**
     * 取消用户授权的角色
     * @param Model $userModel
     * @param array $role_id
     * @return bool
     * @throws \Exception
     */
    public function cancelRoles(Model $userModel, array $role_id)
    {
        $userModel->cancelRoles($role_id);
        return true;
    }

    /**
     * 同步用户角色
     * @param Model $userModel
     * @param array $role_id
     * @return bool
     * @throws \think\Exception
     * @throws \Exception
     */
    public function syncRoles(Model $userModel, array $role_id)
    {
        $userModel->diffRoles($role_id);
        return true;
    }
}