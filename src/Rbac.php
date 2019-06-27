<?php

namespace Lihq1403\ThinkRbac;

use Lihq1403\ThinkRbac\exception\DataValidationException;
use Lihq1403\ThinkRbac\exception\ForbiddenException;
use Lihq1403\ThinkRbac\exception\InvalidArgumentException;
use Lihq1403\ThinkRbac\model\PermissionGroup;
use Lihq1403\ThinkRbac\model\UserRole;
use Lihq1403\ThinkRbac\model\Permission;
use Lihq1403\ThinkRbac\model\RolePermissionGroup;
use Lihq1403\ThinkRbac\protected_traits\PermissionGroupOperation;
use Lihq1403\ThinkRbac\protected_traits\PermissionOperation;
use Lihq1403\ThinkRbac\protected_traits\RoleOperation;
use Lihq1403\ThinkRbac\service\PermissionGroupService;
use think\facade\Request;
use think\Validate;

/**
 * 公共方法函数
 */
require_once 'helper/function.php';


class Rbac
{
    /**
     * 权限组
     */
    use PermissionGroupOperation;


    /**
     * 权限
     */
    use PermissionOperation;

    /**
     * 角色
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
        $roles_id = UserRole::where('admin_user_id', $user_id)->column('role_id');
        if (empty($roles_id)) {
            throw new ForbiddenException('用户暂无分配角色');
        }

        // 获取用户所持有的权限id组
        $permissions_id = RolePermissionGroup::whereIn('role_id', $roles_id)->column('permission_id');
        if (!in_array($permission_id, $permissions_id)) {
            throw new ForbiddenException('无权访问');
        }
        return true;
    }

}