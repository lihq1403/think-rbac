<?php
namespace Lihq1403\ThinkRbac\protected_traits;

use Lihq1403\ThinkRbac\exception\DataValidationException;
use Lihq1403\ThinkRbac\exception\InvalidArgumentException;
use Lihq1403\ThinkRbac\helper\PageHelper;
use Lihq1403\ThinkRbac\model\Permission;
use Lihq1403\ThinkRbac\service\PermissionGroupService;
use Lihq1403\ThinkRbac\service\PermissionService;
use think\facade\Validate;

trait PermissionOperation
{
    /**
     * 获取所有权限
     * @param array $field
     * @param array $map
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function allPermission(array $field = [], array $map = [])
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
    public function addPermission(string $name, string $controller, string $action, string $description, string $permission_group_code, string $behavior, $module = 'admin')
    {
        // 转换权限组id
        $permission_group_id = PermissionGroupService::instance()->findIdByCode($permission_group_code);
        if (empty($permission_group_id)) {
            throw new DataValidationException('error permission_group_code');
        }

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
        $validate = Validate::rule([
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

        return Permission::create($data);
    }

    /**
     * 编辑权限规则
     * @param $permission_id
     * @param array $update_data
     * @return Permission
     * @throws DataValidationException
     * @throws InvalidArgumentException
     */
    public function editPermission(int $permission_id, array $update_data)
    {
        if (empty($update_data)) {
            throw new InvalidArgumentException('无更新');
        }
        if (!empty($update_data['permission_group_code'])) {
            // 转换权限组id
            $permission_group_id = PermissionGroupService::instance()->findIdByCode($update_data['permission_group_code']);
            if (empty($permission_group_id)) {
                throw new DataValidationException('error permission_group_code');
            }
        }

        // 数据整理
        $update_data = [
            'id' =>$permission_id,
            'name' => $update_data['name'] ?? '',
            'description' => $update_data['description'] ?? '',
            'module' => $update_data['module'] ?? '',
            'controller' => $update_data['controller'] ?? '',
            'action' => $update_data['action'] ?? '',
            'permission_group_id' => $permission_group_id ?? 0,
            'behavior' => $update_data['behavior'] ?? '',
        ];

        // 去掉空字符串数据
        $update_data = array_del_empty($update_data);

        // 数据验证
        $validate = Validate::rule([
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

        return Permission::update($update_data);
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

    /**
     * 获取所有权限 分页
     * @param array $map
     * @param array $field
     * @param string $order
     * @param int $page
     * @param int $page_rows
     * @return array
     */
    public function getPermissions(array $map = [], array $field = [], string $order = 'id desc', int $page = 1, int $page_rows = 10)
    {
        $with = [
            'permission_group'
        ];
        return (new PageHelper(new Permission()))->with($with)->where($map)->order($order)->setFields($field)->page($page)->pageRows($page_rows)->result();
    }
}