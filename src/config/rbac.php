<?php


return [

    /**
     * 用户表
     */
    'user_model' => \app\common\models\AdminUser::class,

    /**
     * 是否忽略未定义的权限
     */
    'skip_undefined_permission' => true,

    /**
     * 可以跳过权限验证的方法
     */
    'continue_list' => [
        'module' => ['admin'],
        'controller' => ['test'],
        'action' => ['test']
    ],

    /**
     * 权限组配置，很重要！！！
     */
    'permission_group_list' => [
        ['name' => 'rbac管理', 'code' => 'rbac'],

        // todo 自己手动配置权限组
    ],

    /**
     * 权限配置
     * $behavior => ['list', 'add', 'edit', 'show', 'delete', 'import', 'export', 'download'];
     */
    'permission_list' => [

        /**
         * rbac管理
         */

        // 角色管理
        ['name' => '角色新增', 'module' => 'admin', 'controller' => 'rbac', 'action' => 'addRole', 'behavior' => 'add', 'permission_group_code' => 'rbac'],
        ['name' => '角色编辑', 'module' => 'admin', 'controller' => 'rbac', 'action' => 'editRole', 'behavior' => 'edit', 'permission_group_code' => 'rbac'],
        ['name' => '角色删除', 'module' => 'admin', 'controller' => 'rbac', 'action' => 'delRole', 'behavior' => 'delete', 'permission_group_code' => 'rbac'],
        ['name' => '角色列表', 'module' => 'admin', 'controller' => 'rbac', 'action' => 'getRoles', 'behavior' => 'list', 'permission_group_code' => 'rbac'],
        ['name' => '角色拥有的权限列表', 'module' => 'admin', 'controller' => 'rbac', 'action' => 'roleHoldPermissionGroup', 'behavior' => 'list', 'permission_group_code' => 'rbac'],
        ['name' => '角色更换的权限列表', 'module' => 'admin', 'controller' => 'rbac', 'action' => 'diffPermissionGroup', 'behavior' => 'edit', 'permission_group_code' => 'rbac'],
        // 权限组管理
        ['name' => '权限组新增', 'module' => 'admin', 'controller' => 'rbac', 'action' => 'addPermissionGroup', 'behavior' => 'add', 'permission_group_code' => 'rbac'],
        ['name' => '权限组编辑', 'module' => 'admin', 'controller' => 'rbac', 'action' => 'editPermissionGroup', 'behavior' => 'edit', 'permission_group_code' => 'rbac'],
        ['name' => '权限组删除', 'module' => 'admin', 'controller' => 'rbac', 'action' => 'delPermissionGroup', 'behavior' => 'delete', 'permission_group_code' => 'rbac'],
        ['name' => '权限组列表', 'module' => 'admin', 'controller' => 'rbac', 'action' => 'getPermissionGroups', 'behavior' => 'list', 'permission_group_code' => 'rbac'],
        // 权限管理
        ['name' => '权限新增', 'module' => 'admin', 'controller' => 'rbac', 'action' => 'addPermission', 'behavior' => 'add', 'permission_group_code' => 'rbac'],
        ['name' => '权限编辑', 'module' => 'admin', 'controller' => 'rbac', 'action' => 'editPermission', 'behavior' => 'edit', 'permission_group_code' => 'rbac'],
        ['name' => '权限删除', 'module' => 'admin', 'controller' => 'rbac', 'action' => 'delPermission', 'behavior' => 'delete', 'permission_group_code' => 'rbac'],
        ['name' => '权限列表', 'module' => 'admin', 'controller' => 'rbac', 'action' => 'getPermissions', 'behavior' => 'list', 'permission_group_code' => 'rbac'],
        // 管理员管理
        ['name' => '给管理员分配角色', 'module' => 'admin', 'controller' => 'rbac', 'action' => 'userAssignRoles', 'behavior' => 'add', 'permission_group_code' => 'rbac'],
        ['name' => '给管理员取消角色', 'module' => 'admin', 'controller' => 'rbac', 'action' => 'userCancelRoles', 'behavior' => 'delete', 'permission_group_code' => 'rbac'],
        ['name' => '给管理员同步角色', 'module' => 'admin', 'controller' => 'rbac', 'action' => 'userSyncRoles', 'behavior' => 'edit', 'permission_group_code' => 'rbac'],
        // 日志管理
        ['name' => '日志列表', 'module' => 'admin', 'controller' => 'rbac', 'action' => 'getLog', 'behavior' => 'list', 'permission_group_code' => 'rbac'],

        // todo 手动配置权限
    ],
];