<?php

namespace Lihq1403\ThinkRbac;

use Lihq1403\ThinkRbac\traits\PermissionOperation;
use Lihq1403\ThinkRbac\traits\RoleOperation;
use Lihq1403\ThinkRbac\traits\RolePermissionOperation;

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
     * 角色关联权限
     */
    use RolePermissionOperation;

    /**
     * 用户关联角色
     */


    // 添加管理员

    // 添加角色



    // 管理员关联角色

    // 角色关联权限

    // 删除管理员

    // 删除角色

    // 删除权限

    // 删除管理员关联角色

    // 删除角色关联权限

    // 检查是否具有权限
}