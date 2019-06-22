<?php
namespace Lihq1403\ThinkRbac\traits;

use Lihq1403\ThinkRbac\exception\DataValidationException;
use Lihq1403\ThinkRbac\exception\InvalidArgumentException;
use Lihq1403\ThinkRbac\model\Permission;
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
     * @param $behavior
     * @param string $module
     * @return Permission
     * @throws DataValidationException
     */
    public function addPermission($name, $controller, $action, $description, $group, $behavior, $module = 'admin')
    {
        $data = [
            'name' => $name,
            'controller' => $controller,
            'action' => $action,
            'description' => $description,
            'group' => $group,
            'behavior' => $behavior,
            'module' => $module,
        ];

        // 数据验证
        $validate = Validate::make([
            'name|权限名称' => 'require|max:255|unique:Lihq1403\ThinkRbac\model\Permission,name',
            'controller|访问controller' => 'require|max:255',
            'action|访问action' => 'require|max:255',
            'description|权限描述' => 'require|max:255',
            'group|所属类别' => 'require|max:255',
            'behavior|权限行为' => 'require|max:255|in:list,add,edit,show,delete,import,export,download',
            'module|访问module' => 'require|max:255',
        ]);
        if (!$validate->check($data)) {
            throw new DataValidationException($validate->getError());
        }

        $model = new Permission();
        return $model->savePermission($data);
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
        // 数据整理
        $update_data = [
            'id' =>$permission_id,
            'name' => $update_data['name'] ?? '',
            'controller' => $update_data['controller'] ?? '',
            'action' => $update_data['action'] ?? '',
            'description' => $update_data['description'] ?? '',
            'group' => $update_data['group'] ?? '',
            'behavior' => $update_data['behavior'] ?? '',
            'module' => $update_data['module'] ?? '',
        ];

        // 去掉空字符串数据
        $update_data = array_filter($update_data,function ($var) {
            if($var === '' || $var === null)
            {
                return false;
            }
            return true;
        });

        // 数据验证
        $validate = Validate::make([
            'id|权限id' => 'require|number|max:10|gt:0',
            'name|权限名称' => 'max:255|unique:Lihq1403\ThinkRbac\model\Permission,name,'.$permission_id,
            'controller|访问controller' => 'max:255',
            'action|访问action' => 'max:255',
            'description|描述' => 'max:255',
            'group|所属类别' => 'max:255',
            'behavior|行为' => 'max:255|in:list,add,edit,show,delete,import,export,download',
            'module|访问module' => 'max:255',
        ]);
        if (!$validate->check($update_data)) {
            throw new DataValidationException($validate->getError());
        }

        $model = new Permission();
        return $model->savePermission($update_data);
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

        $model = new Permission();
        $model->delPermission($permission_id);
        return true;
    }
}