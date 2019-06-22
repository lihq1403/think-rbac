<?php

namespace Lihq1403\ThinkRbac\traits;

use Lihq1403\ThinkRbac\exception\DataValidationException;
use Lihq1403\ThinkRbac\exception\InvalidArgumentException;
use Lihq1403\ThinkRbac\model\Role;
use think\Validate;

trait RoleOperation
{
    public function getRole($map = [], $field = [], $page = 1, $page_rows = 10)
    {
        $model = new Role();
        return $model->getList($map, $field, $page, $page_rows);
    }

    /**
     * 添加角色
     * @param $name
     * @param string $description
     * @return Role
     * @throws DataValidationException
     */
    public function addRole($name, $description = '')
    {
        $data = [
            'name' => $name,
            'description' => $description,
        ];

        // 数据验证
        $validate = Validate::make([
            'name|角色名称' => 'require|max:255|unique:Lihq1403\ThinkRbac\model\Role,name',
            'description|角色描述' => 'max:255',
        ]);
        if (!$validate->check($data)) {
            throw new DataValidationException($validate->getError());
        }

        $model = new Role();
        return $model->saveRole($data);
    }

    /**
     * 编辑角色
     * @param $role_id
     * @param array $update_data
     * @return Role
     * @throws DataValidationException
     * @throws InvalidArgumentException
     */
    public function editRole($role_id, array $update_data)
    {
        if (empty($update_data)) {
            throw new InvalidArgumentException('无更新');
        }
        // 数据整理
        $update_data = [
            'id' =>$role_id,
            'name' => $update_data['name'] ?? '',
            'description' => $update_data['description'] ?? '',
        ];

        // 数据验证
        $validate = Validate::make([
            'name|角色名称' => 'max:255|unique:Lihq1403\ThinkRbac\model\Role,name,'.$role_id,
            'description|角色描述' => 'max:255',
        ]);
        if (!$validate->check($update_data)) {
            throw new DataValidationException($validate->getError());
        }

        $model = new Role();
        return $model->saveRole($update_data);
    }

    /**
     * 删除角色
     * @param $role_id
     * @return bool
     * @throws InvalidArgumentException
     */
    public function delRole($role_id)
    {
        if (empty($role_id)) {
            throw new InvalidArgumentException('无效id');
        }
        $model = new Role();
        $model->delRole($role_id);

        // todo 删除相关角色权限关联

        // todo 删除相关用户角色关联

        return true;

    }
}