<?php
namespace Lihq1403\ThinkRbac\protected_traits;

use Lihq1403\ThinkRbac\exception\DataValidationException;
use Lihq1403\ThinkRbac\exception\InvalidArgumentException;
use Lihq1403\ThinkRbac\model\Permission;
use Lihq1403\ThinkRbac\service\PermissionGroupService;
use Lihq1403\ThinkRbac\service\PermissionService;
use think\Validate;

trait PermissionOperation
{
    /**
     * 获取所有权限
     * @param array $map
     * @param array $field
     * @return false|\think\db\Query[]
     * @throws \think\Exception\DbException
     */
    public function allPermission($field = [], $map = [])
    {
        $model = new Permission();
        return $model->getList($map, $field);
    }

    /**
     * 添加权限规则
     * @param $name
     * @param $controller
     * @param $action
     * @param $description
     * @param $permission_group_code
     * @param $behavior
     * @param string $module
     * @return Permission
     * @throws DataValidationException
     */
    public function addPermission($name, $controller, $action, $description, $permission_group_code, $behavior, $module = 'admin')
    {
        // 转换权限组id
        $permission_group_id = PermissionGroupService::instance()->findIdByCode($permission_group_code);

        $data = [
            'name' => $name,
            'description' => $description,
            'module' => $module,
            'controller' => $controller,
            'action' => $action,
            'behavior' => $behavior,
            'permission_group_id' => $permission_group_id,
        ];

        // 数据验证
        $validate = Validate::make([
            'name|权限名称' => 'require|max:255|unique:Lihq1403\ThinkRbac\model\Permission,name',
            'description|权限描述' => 'require|max:255',
            'module|访问module' => 'require|max:255',
            'controller|访问controller' => 'require|max:255',
            'action|访问action' => 'require|max:255',
            'permission_group_id|所属组' => 'require|max:255',
            'behavior|权限行为' => 'require|max:255|in:'.implode(',',Permission::BEHAVIOR),
        ]);
        if (!$validate->check($data)) {
            throw new DataValidationException($validate->getError());
        }

        return PermissionService::instance()->saveData($data);
    }

    /**
     * 编辑权限规则
     * @param $permission_id
     * @param array $update_data
     * @return Permission
     * @throws DataValidationException
     * @throws InvalidArgumentException
     */
    public function editPermission($permission_id, array $update_data)
    {
        if (empty($update_data)) {
            throw new InvalidArgumentException('无更新');
        }
        if (!empty($update_data['permission_group_code'])) {
            // 转换权限组id
            $permission_group_id = PermissionGroupService::instance()->findIdByCode($update_data['permission_group_code']);
        }


        // 数据整理
        $update_data = [
            'id' =>$permission_id,
            'name' => $update_data['name'] ?? '',
            'description' => $update_data['description'] ?? '',
            'module' => $update_data['module'] ?? '',
            'controller' => $update_data['controller'] ?? '',
            'action' => $update_data['action'] ?? '',
            'permission_group_id' => $permission_group_id ?? '',
            'behavior' => $update_data['behavior'] ?? '',
        ];

        // 去掉空字符串数据
        $update_data = array_del_empty($update_data);

        // 数据验证
        $validate = Validate::make([
            'id|权限id' => 'require|number|max:10|gt:0',
            'name|权限名称' => 'max:255|unique:Lihq1403\ThinkRbac\model\Permission,name,'.$permission_id,
            'description|描述' => 'max:255',
            'module|访问module' => 'max:255',
            'controller|访问controller' => 'max:255',
            'action|访问action' => 'max:255',
            'permission_group_id|所属组' => 'max:255',
            'behavior|行为' => 'max:255|in:'.implode(',',Permission::BEHAVIOR),
        ]);
        if (!$validate->check($update_data)) {
            throw new DataValidationException($validate->getError());
        }

        return PermissionService::instance()->saveData($update_data);
    }

    /**
     * 删除权限，可以批量
     * @param $permission_id
     * @return bool
     * @throws InvalidArgumentException
     * @throws \Exception
     */
    public function delPermission($permission_id)
    {
        if (empty($permission_id)) {
            throw new InvalidArgumentException('无效id');
        }
        if (!is_array($permission_id)) {
            $permission_id = [$permission_id];
        }

        PermissionService::instance()->del($permission_id);
        return true;
    }
}