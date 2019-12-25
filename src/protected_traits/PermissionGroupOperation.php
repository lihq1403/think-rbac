<?php

namespace Lihq1403\ThinkRbac\protected_traits;

use Lihq1403\ThinkRbac\exception\DataValidationException;
use Lihq1403\ThinkRbac\exception\InvalidArgumentException;
use Lihq1403\ThinkRbac\model\PermissionGroup;
use Lihq1403\ThinkRbac\service\PermissionGroupService;
use think\facade\Validate;

/**
 * 权限组相关操作
 * Trait PermissionGroupOperation
 * @package Lihq1403\ThinkRbac\protected_traits
 */
trait  PermissionGroupOperation
{
    /**
     * 添加权限组
     * @param $name
     * @param $code
     * @param string $description
     * @return PermissionGroup
     * @throws DataValidationException
     */
    public function addPermissionGroup(string $name, string $code, string $description = '')
    {
        // 数据验证
        $data = [
            'name' => $name,
            'code' => $code,
            'description' => $description
        ];
        // 数据验证
        $validate = Validate::rule([
            'name|权限组名称' => 'require|max:255|unique:Lihq1403\ThinkRbac\model\PermissionGroup',
            'description|权限描述' => 'require|max:255',
            'code|权限组唯一标识' => 'require|max:255|unique:Lihq1403\ThinkRbac\model\PermissionGroup',
        ]);
        if (!$validate->check($data)) {
            throw new DataValidationException($validate->getError());
        }

        return PermissionGroup::create($data);
    }

    /**
     * 编辑权限组
     * @param $permission_group_id
     * @param array $data
     * @return PermissionGroup
     * @throws DataValidationException
     * @throws InvalidArgumentException
     */
    public function editPermissionGroup(int $permission_group_id, array $data)
    {
        if (empty($data)) {
            throw new InvalidArgumentException('无更新');
        }
        // 数据整理
        $update_data = [
            'id' =>$permission_group_id,
            'name' => $data['name'] ?? '',
            'code' => $data['code'] ?? '',
            'description' => $data['description'] ?? '',
        ];
        $update_data = array_del_empty($update_data);

        // 数据验证
        $validate = Validate::rule([
            'name|权限组名称' => 'max:255|unique:Lihq1403\ThinkRbac\model\PermissionGroup,name,'.$permission_group_id,
            'description|权限描述' => 'max:255',
            'code|权限组唯一标识' => 'max:255|unique:Lihq1403\ThinkRbac\model\PermissionGroup,code,'.$permission_group_id,
        ]);
        if (!$validate->check($update_data)) {
            throw new DataValidationException($validate->getError());
        }

        return PermissionGroup::update($update_data);
    }

    /**
     * 删除权限组
     * @param $permission_group_id
     * @return bool
     * @throws \Exception
     */
    public function delPermissionGroup($permission_group_id)
    {
        if (!is_array($permission_group_id)) {
            $permission_group_id = [$permission_group_id];
        }
        PermissionGroupService::instance()->del($permission_group_id);
        return true;
    }
}