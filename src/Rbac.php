<?php

namespace Lihq1403\ThinkRbac;

use Lihq1403\ThinkRbac\exception\ForbiddenException;
use Lihq1403\ThinkRbac\model\AdminUserRole;
use Lihq1403\ThinkRbac\model\Permission;
use Lihq1403\ThinkRbac\model\Role;
use Lihq1403\ThinkRbac\model\RolePermission;
use Lihq1403\ThinkRbac\traits\PermissionOperation;
use Lihq1403\ThinkRbac\traits\RoleOperation;
use think\facade\Request;

class Rbac
{
    /**
     * 权限相关操作方法
     */
    use PermissionOperation;

    /**
     * 角色相关操作方法
     */
    use RoleOperation;

    /**
     * 检查是否具有权限
     * @param $user_id
     * @param string $module
     * @param string $controller
     * @param string $action
     * @return bool
     * @throws ForbiddenException
     */
    public function can($user_id, $module = '', $controller = '', $action = '')
    {
        if (empty($module)) {
            $module = Request::module();
        }
        $module = strtolower($module);
        if (empty($controller)) {
            $controller = Request::controller();
        }
        $controller = strtolower($controller);
        if (empty($action)) {
            $action = Request::action();
        }
        $action = strtolower($action);

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
        $roles_id = AdminUserRole::where('admin_user_id', $user_id)->column('role_id');
        if (empty($roles_id)) {
            throw new ForbiddenException('用户暂无分配角色');
        }

        // 获取用户所持有的权限id组
        $permissions_id = RolePermission::whereIn('role_id', $roles_id)->column('permission_id');
        if (!in_array($permission_id, $permissions_id)) {
            throw new ForbiddenException('无权访问');
        }
        return true;
    }

}