<?php

namespace Lihq1403\ThinkRbac\service;


use Lihq1403\ThinkRbac\model\Role;
use Lihq1403\ThinkRbac\protected_traits\Singleton;

class RoleService
{
    use Singleton;

    /**
     * 保存数据
     * @param $data
     * @return Role
     */
    public function saveData($data)
    {
        $model = new Role();
        return $model->saveData($data);
    }

    /**
     * 禁用角色
     * @param array $ids
     * @return bool
     */
    public function close(array $ids)
    {
        $model = new Role();
        $model->whereIn('id', $ids)->data(['status' => 0])->update();
        return true;
    }
}