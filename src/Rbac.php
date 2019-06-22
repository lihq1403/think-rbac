<?php

namespace Lihq1403\ThinkRbac;

use Lihq1403\ThinkRbac\traits\PermissionOperation;
use Lihq1403\ThinkRbac\traits\RoleOperation;

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

    // 检查是否具有权限
}