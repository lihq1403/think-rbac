<?php


return [

    /**
     * 用户表
     */
    'user_model' => \app\common\model\AdminUser::class,

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
];