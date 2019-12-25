<?php

namespace Lihq1403\ThinkRbac\model;

/**
 * 权限表
 * Class Permission
 * @package Lihq1403\ThinkRbac\model
 */
class Permission extends BaseModel
{
    public $name = 'rbac_permission';

    const BEHAVIOR = ['list', 'add', 'edit', 'show', 'delete', 'import', 'export', 'download'];

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function setModuleAttr($value)
    {
        return strtolower($value);
    }

    public function setControllerAttr($value)
    {
        // 去除controller
        return str_replace("controller","", strtolower($value));
    }

    public function setActionAttr($value)
    {
        return strtolower($value);
    }

    /**
     * 删除权限
     * @param $permission_id
     * @return bool
     * @throws \Exception
     */
    public function delPermission($permission_id)
    {
        if (!is_array($permission_id)) {
            $permission_id = [$permission_id];
        }
        return self::destroy($permission_id);
    }

    /**
     * 获取所有权限
     * @param $map
     * @param $field
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getList($map, $field)
    {
        return self::where($map)->field($field)->select()->toArray();
    }
}