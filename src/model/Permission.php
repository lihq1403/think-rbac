<?php

namespace Lihq1403\ThinkRbac\model;

/**
 * 权限表
 * Class Permission
 * @package Lihq1403\ThinkRbac\model
 */
class Permission extends BaseModel
{
    public $name = 'lihq1403_permission';

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    /**
     * 保存权限
     * @param array $data
     * @return $this
     */
    public function savePermission($data = [])
    {
        if (!empty($data)) {
            $this->data($data);
        }
        if (!empty($data['id'])) {
            $this->isUpdate(true);
        }
        $this->allowField(true)->save();
        return $this;
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
        // 权限关联角色也要被删掉
        RolePermission::whereIn('permission_id', $permission_id)->delete();
        return self::destroy($permission_id);
    }

    /**
     * 获取所有权限
     * @param $map
     * @param $field
     * @return false|\think\db\Query[]
     * @throws \think\Exception\DbException
     */
    public function getList($map, $field)
    {
        return self::where($map)->field($field)->all();
    }
}