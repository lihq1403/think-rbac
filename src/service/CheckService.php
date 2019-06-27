<?php

namespace Lihq1403\ThinkRbac\service;

use Lihq1403\ThinkRbac\exception\ForbiddenException;
use Lihq1403\ThinkRbac\model\Permission;
use Lihq1403\ThinkRbac\model\RolePermissionGroup;
use Lihq1403\ThinkRbac\model\UserRole;
use Lihq1403\ThinkRbac\protected_traits\Singleton;

class CheckService
{
    use Singleton;

    /**
     * 是否可以跳过，白名单
     * @param $module
     * @param $controller
     * @param $action
     * @return bool
     */
    public function canSkip($module, $controller, $action)
    {
        $continue_list = config('rbac.continue_list');

        $continue_module = array_map(function ($value){
            return strtolower($value);
        }, $continue_list['module']);
        $continue_controller = array_map(function ($value){
            return str_replace("controller","", strtolower($value));
        }, $continue_list['controller']);
        $continue_action = array_map(function ($value){
            return strtolower($value);
        }, $continue_list['action']);

        if (!in_array($module, $continue_module) || !in_array($controller, $continue_controller) || !in_array($action, $continue_action)) {
            return false;
        }
        return true;
    }

    /**
     * 检查用户权限
     * @param $user_id
     * @param $module
     * @param $controller
     * @param $action
     * @return bool
     * @throws ForbiddenException
     */
    public function checkPermission($user_id, $module, $controller, $action)
    {
        // 获得权限id
        $map = [
            ['module', '=', $module],
            ['controller', '=', $controller],
            ['action', '=', $action],
        ];
        $permission_id = Permission::where($map)->value('id');
        if (empty($permission_id)) {
            if (config('rbac.skip_undefined_permission')) {
                return true;
            }
            throw new ForbiddenException('权限规则未定义');
        }

        // 获取用户的角色
        $roles_id = UserRole::where('user_id', $user_id)->column('role_id');
        if (empty($roles_id)) {
            throw new ForbiddenException('用户暂无分配角色');
        }

        // 获取角色所持有的权限组
        $permission_group_id = RolePermissionGroup::whereIn('role_id', $roles_id)->column('permission_group_id');
        if (empty($permission_group_id)) {
            throw new ForbiddenException('角色未拥有权限组');
        }
        // 查看权限组下的权限
        $permissions_id = Permission::whereIn('permission_group_id', $permission_group_id)->column('id');
        if (!in_array($permission_id, $permissions_id)) {
            throw new ForbiddenException('无权访问');
        }
        return true;
    }
}