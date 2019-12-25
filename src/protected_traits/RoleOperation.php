<?php

namespace Lihq1403\ThinkRbac\protected_traits;

use Lihq1403\ThinkRbac\exception\DataValidationException;
use Lihq1403\ThinkRbac\exception\InvalidArgumentException;
use Lihq1403\ThinkRbac\helper\PageHelper;
use Lihq1403\ThinkRbac\model\Permission;
use Lihq1403\ThinkRbac\model\Role;
use Lihq1403\ThinkRbac\model\RolePermissionGroup;
use Lihq1403\ThinkRbac\service\PermissionGroupService;
use Lihq1403\ThinkRbac\service\PermissionService;
use Lihq1403\ThinkRbac\service\RoleService;
use think\facade\Validate;

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
    public function getRoles(array $map = [], array $field = [], string $order = 'id desc', int $page = 1, int $page_rows = 10)
    {
        return (new PageHelper(new Role()))->where($map)->order($order)->setFields($field)->page($page)->pageRows($page_rows)->result();
    }

    /**
     * 获取角色的权限组列表
     * @param $role_id
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function roleHoldPermissionGroup(int $role_id)
    {
        return RoleService::instance()->roleHoldPermissionGroup($role_id);
    }

    /**
     * 添加角色
     * @param $name
     * @param string $description
     * @return Role
     * @throws DataValidationException
     */
    public function addRole(string $name, string $description = '')
    {
        $data = [
            'name' => $name,
            'description' => $description,
        ];

        // 数据验证
        $validate = Validate::rule([
            'name|角色名称' => 'require|max:255|unique:Lihq1403\ThinkRbac\model\Role,name',
            'description|角色描述' => 'max:255',
        ]);
        if (!$validate->check($data)) {
            throw new DataValidationException($validate->getError());
        }
        return Role::create($data);
    }

    /**
     * 编辑角色
     * @param $role_id
     * @param array $update_data
     * @return Role
     * @throws DataValidationException
     * @throws InvalidArgumentException
     */
    public function editRole(int $role_id, array $update_data)
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
        $validate = Validate::rule([
            'name|角色名称' => 'max:255|unique:Lihq1403\ThinkRbac\model\Role,name,'.$role_id,
            'description|角色描述' => 'max:255',
        ]);
        if (!$validate->check($update_data)) {
            throw new DataValidationException($validate->getError());
        }

        return Role::update($update_data);
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
     * 角色分配权限 权限组分配
     * @param int $role_id
     * @param array $group_code
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function assignPermissionGroup(int $role_id, $group_code = [])
    {
        $model = Role::find($role_id);
        if (empty($model)){
            return false;
        }

        if (!is_array($group_code)) {
            $group_code = [$group_code];
        }
        if (empty($group_code)) {
            // 如果为空，则添加所有权限组
            $permissions_group_id = PermissionGroupService::instance()->findAllIds();
        } else {
            $permissions_group_id = PermissionGroupService::instance()->findIdsByCodes($group_code);
        }

        if (empty($permissions_group_id)) {
            return false;
        }
        return $model->assignPermissionGroup($permissions_group_id);
    }

    /**
     * 取消分配权限 group
     * @param int $role_id
     * @param array $group_code
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function cancelPermissionGroup(int $role_id, $group_code = [])
    {
        $model = Role::find($role_id);
        if (empty($model)){
            return false;
        }
        if (!is_array($group_code)) {
            $group_code = [$group_code];
        }

        if (empty($group_code)) {
            // 如果为空，则添加所有权限组
            $permissions_group_id = PermissionGroupService::instance()->findAllIds();
        } else {
            $permissions_group_id = PermissionGroupService::instance()->findIdsByCodes($group_code);
        }
        if (empty($permissions_group_id)) {
            return false;
        }
        return $model->cancelPermission($permissions_group_id);
    }

    /**
     * 更换角色权限，差值
     * @param int $role_id
     * @param $group_code
     * @return bool
     * @throws \Exception
     */
    public function diffPermissionGroup(int $role_id, array $group_code)
    {
        // 获取已有权限组
        $has_permission_group_id = RolePermissionGroup::where('role_id', $role_id)->column('permission_group_id') ?? [];

        $permissions_group_id = PermissionGroupService::instance()->findIdsByCodes($group_code);

        // 筛选需要删除，还是新增的权限组
        $del = array_diff($has_permission_group_id, $permissions_group_id);
        $add = array_diff($permissions_group_id, $has_permission_group_id);

        if (!empty($del)) {
            RolePermissionGroup::destroy(function ($query) use ($role_id, $del) {
                $query->where('role_id', $role_id)->whereIn('permission_group_id', $del);
            });
        }

        if (!empty($add)) {
            $add_data = [];
            foreach ($add as $a) {
                $add_data[] = [
                    'role_id' => $role_id,
                    'permission_group_id' => $a
                ];
            }
            $model = new RolePermissionGroup();
            $model->saveAll($add_data);
        }

        return true;
    }


}