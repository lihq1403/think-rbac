<?php

namespace Lihq1403\ThinkRbac;

use Lihq1403\ThinkRbac\exception\DataValidationException;
use Lihq1403\ThinkRbac\exception\ForbiddenException;
use Lihq1403\ThinkRbac\exception\InvalidArgumentException;
use Lihq1403\ThinkRbac\model\PermissionGroup;
use Lihq1403\ThinkRbac\model\UserRole;
use Lihq1403\ThinkRbac\model\Permission;
use Lihq1403\ThinkRbac\model\RolePermissionGroup;
use Lihq1403\ThinkRbac\protected_traits\LogOperation;
use Lihq1403\ThinkRbac\protected_traits\PermissionGroupOperation;
use Lihq1403\ThinkRbac\protected_traits\PermissionOperation;
use Lihq1403\ThinkRbac\protected_traits\RoleOperation;
use Lihq1403\ThinkRbac\service\CheckService;
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
     * 日志
     */
    use LogOperation;

    /**
     * 检查是否具有访问权限
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
            $module = app('http')->getName();
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

        if (empty($controller) || empty($action)) {
            // 如果是空的，应该的引入了Lihq1403的完整类名，所以拿不到
            $rule = Request::rule()->getName();
            $rule = explode('@', $rule);
            $controller = 'rbac';
            $action = strtolower($rule[1] ?? '');
        }

        // 检查是否可以跳过
        if (CheckService::instance()->canSkip($module, $controller, $action)) {
            return true;
        }

        CheckService::instance()->checkPermission($user_id, $module, $controller, $action);
        return true;
    }

}