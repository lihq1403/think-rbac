<?php

namespace Lihq1403\ThinkRbac\protected_traits;

use Lihq1403\ThinkRbac\exception\DataValidationException;
use Lihq1403\ThinkRbac\exception\InvalidArgumentException;
use Lihq1403\ThinkRbac\model\Permission;
use Lihq1403\ThinkRbac\model\Role;
use Lihq1403\ThinkRbac\service\RoleService;
use think\Validate;

trait RoleOperation
{
    /**
     * 获取所有角色 分页
     * @param array $map
     * @param array $field
     * @param int $page
     * @param int $page_rows
     * @return array
     */
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
        return RoleService::instance()->saveData($data);
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

        // 去掉空字符串数据
        $update_data = array_del_empty($update_data);

        // 数据验证
        $validate = Validate::make([
            'name|角色名称' => 'max:255|unique:Lihq1403\ThinkRbac\model\Role,name,'.$role_id,
            'description|角色描述' => 'max:255',
        ]);
        if (!$validate->check($update_data)) {
            throw new DataValidationException($validate->getError());
        }

        return RoleService::instance()->saveData($update_data);
    }

    /**
     * 删除角色
     * @param $role_id
     * @return bool
     * @throws InvalidArgumentException
     * @throws \Exception
     */
    public function delRole($role_id)
    {
        if (empty($role_id)) {
            throw new InvalidArgumentException('无效id');
        }
        $model = new Role();
        $model->delRole($role_id);
        return true;
    }

    /**
     * 禁用角色
     * @param $role_id
     * @return bool
     * @throws InvalidArgumentException
     */
    public function closeRole($role_id)
    {
        if (empty($role_id)) {
            throw new InvalidArgumentException('无效id');
        }
        if (!is_array($role_id)) {
            $role_id = [$role_id];
        }
        RoleService::instance()->close($role_id);
        return true;
    }

    /**
     * 角色分配权限 id分配
     * @param $role_id
     * @param array $permissions_id
     * @return bool
     */
    public function assignPermission(int $role_id, array $permissions_id)
    {
        $model = Role::get($role_id);
        if (empty($model)){
            return false;
        }
        return $model->assignPermission($permissions_id);
    }

    /**
     * 角色分配权限 权限组分配
     * @param int $role_id
     * @param string $group
     * @return bool
     */
    public function assignPermissionGroup(int $role_id, string $group)
    {
        $model = Role::get($role_id);
        if (empty($model)){
            return false;
        }
        // 查找组下的所有权限id
        $permissions_id = Permission::where('group', $group)->column('id');
        if (empty($permissions_id)) {
            return false;
        }
        return $model->assignPermission($permissions_id);
    }

    /**
     * 取消分配权限 id
     * @param int $role_id
     * @param array $permissions_id
     * @return bool
     */
    public function cancelPermission(int $role_id, array $permissions_id)
    {
        $model = Role::get($role_id);
        if (empty($model)){
            return false;
        }
        return $model->cancelPermission($permissions_id);
    }

    /**
     * 取消分配权限 group
     * @param int $role_id
     * @param string $group
     * @return bool
     */
    public function cancelPermissionGroup(int $role_id, string $group)
    {
        $model = Role::get($role_id);
        if (empty($model)){
            return false;
        }
        // 查找组下的所有权限id
        $permissions_id = Permission::where('group', $group)->column('id');
        if (empty($permissions_id)) {
            return false;
        }
        return $model->cancelPermission($permissions_id);
    }


}