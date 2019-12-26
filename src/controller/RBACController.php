<?php

namespace Lihq1403\ThinkRbac\controller;

use Lihq1403\ThinkRbac\exception\DataValidationException;
use Lihq1403\ThinkRbac\exception\InvalidArgumentException;
use Lihq1403\ThinkRbac\lib\RBACLib;

class RBACController extends BaseController
{
    public $AdminUser = null;

    /**
     * @throws InvalidArgumentException
     */
    public function initialize()
    {
        $this->AdminUser = config('rbac.user_model');
        if (empty($this->AdminUser)) {
            throw new InvalidArgumentException('请配置user_model');
        }
    }

    /**
     * -------------
     *    角色管理
     * -------------
     */

    /**
     * 添加角色
     * @return \think\response\Json|\think\response\Jsonp
     * @throws \Lihq1403\ThinkRbac\exception\DataValidationException
     */
    public function addRole()
    {
        $params = $this->apiParams(['name', 'description']);

        return $this->successResponse('success', RBACLib::instance()->addRole($params['name'] ?? '', $params['description'] ?? ''));
    }

    /**
     * 修改角色
     * @return \think\response\Json|\think\response\Jsonp
     * @throws \Lihq1403\ThinkRbac\exception\DataValidationException
     * @throws \Lihq1403\ThinkRbac\exception\InvalidArgumentException
     */
    public function editRole()
    {
        $params = $this->apiParams(['id', 'name', 'description']);

        if (empty($params['id']) || !is_numeric($params['id']) || $params['id'] < 0) {
            throw new DataValidationException('请输入正确的角色id');
        }

        $role_id = (int)$params['id'];
        unset($params['id']);

        $update_data = $params;

        return $this->successResponse('修改成功', RBACLib::instance()->editRole($role_id, $update_data));
    }

    /**
     * 删除角色
     * @return \think\response\Json|\think\response\Jsonp
     * @throws DataValidationException
     * @throws \Lihq1403\ThinkRbac\exception\InvalidArgumentException
     */
    public function delRole()
    {
        $params = $this->apiParams(['id']);

        if (empty($params['id'])) {
            throw new DataValidationException('请输入正确的角色id');
        }

        RBACLib::instance()->delRole($params['id']);

        return $this->successResponse('删除成功');
    }

    /**
     * 获取角色 列表
     * @return \think\response\Json|\think\response\Jsonp
     * @throws DataValidationException
     */
    public function getRoles()
    {
        $params = $this->apiParams(['page', 'page_rows']);

        $pagination = pagination($params);

        return $this->successResponse('获取成功', RBACLib::instance()->getRoles($pagination['page'], $pagination['page_rows']));
    }

    /**
     * -------------
     *   权限组管理
     * -------------
     */

    /**
     * 权限组新增
     * @return \think\response\Json|\think\response\Jsonp
     * @throws DataValidationException
     * @throws \Lihq1403\ThinkRbac\exception\DataValidationException
     */
    public function addPermissionGroup()
    {
        $params = $this->apiParams(['name', 'code', 'description']);

        return $this->successResponse('success', RBACLib::instance()->addPermissionGroup($params['name'] ?? '', $params['code'] ?? '', $params['description'] ?? ''));
    }

    /**
     * 权限组编辑
     * @return \think\response\Json|\think\response\Jsonp
     * @throws DataValidationException
     * @throws \Lihq1403\ThinkRbac\exception\DataValidationException
     * @throws \Lihq1403\ThinkRbac\exception\InvalidArgumentException
     */
    public function editPermissionGroup()
    {
        $params = $this->apiParams(['id', 'name', 'code', 'description']);

        if (empty($params['id']) || !is_numeric($params['id']) || $params['id'] < 0) {
            throw new DataValidationException('请输入正确的权限组id');
        }

        $permission_group_id = (int)$params['id'];
        unset($params['id']);

        $update_data = $params;

        return $this->successResponse('修改成功', RBACLib::instance()->editPermissionGroup($permission_group_id, $update_data));
    }

    /**
     * 权限组列表
     * @return \think\response\Json|\think\response\Jsonp
     * @throws DataValidationException
     */
    public function getPermissionGroups()
    {
        $params = $this->apiParams(['page', 'page_rows']);

        $pagination = pagination($params);

        return $this->successResponse('获取成功', RBACLib::instance()->getPermissionGroups($pagination['page'], $pagination['page_rows']));
    }

    /**
     * 权限组删除
     * @return \think\response\Json|\think\response\Jsonp
     * @throws DataValidationException
     * @throws \Exception
     */
    public function delPermissionGroup()
    {
        $params = $this->apiParams(['id']);

        if (empty($params['id'])) {
            throw new DataValidationException('请输入正确的权限组id');
        }

        RBACLib::instance()->delPermissionGroup($params['id']);

        return $this->successResponse('删除成功');
    }

    /**
     * -------------
     *   权限管理
     * -------------
     */

    /**
     * 权限新增
     * @return \think\response\Json|\think\response\Jsonp
     * @throws DataValidationException
     * @throws \Lihq1403\ThinkRbac\exception\DataValidationException
     */
    public function addPermission()
    {
        $params = $this->apiParams(['name', 'controller', 'action', 'description', 'permission_group_code', 'behavior', 'module']);

        return $this->successResponse('添加成功', RBACLib::instance()->addPermission($params['name'] ?? '', $params['controller'] ?? '', $params['action'] ?? '', $params['description'] ?? '', $params['permission_group_code'] ?? '', $params['behavior'] ?? '', $params['module'] ?? 'admin'));
    }

    /**
     * 权限编辑
     * @return \think\response\Json|\think\response\Jsonp
     * @throws DataValidationException
     * @throws \Lihq1403\ThinkRbac\exception\DataValidationException
     * @throws \Lihq1403\ThinkRbac\exception\InvalidArgumentException
     */
    public function editPermission()
    {
        $params = $this->apiParams(['id', 'name', 'controller', 'action', 'description', 'permission_group_code', 'behavior', 'module']);
        if (empty($params['id']) || !is_numeric($params['id']) || $params['id'] < 0) {
            throw new DataValidationException('请输入正确的权限id');
        }

        $permission_id = (int)$params['id'];
        unset($params['id']);

        $update_data = $params;
        return $this->successResponse('修改成功', RBACLib::instance()->editPermission($permission_id, $update_data));
    }

    /**
     * 权限删除
     * @return \think\response\Json|\think\response\Jsonp
     * @throws DataValidationException
     * @throws \Lihq1403\ThinkRbac\exception\InvalidArgumentException
     */
    public function delPermission()
    {
        $params = $this->apiParams(['id']);

        if (empty($params['id'])) {
            throw new DataValidationException('请输入正确的权限id');
        }

        RBACLib::instance()->delPermission($params['id']);

        return $this->successResponse('删除成功');
    }

    /**
     * 权限列表
     * @return \think\response\Json|\think\response\Jsonp
     * @throws DataValidationException
     */
    public function getPermissions()
    {
        $params = $this->apiParams(['page', 'page_rows']);

        $pagination = pagination($params);

        return $this->successResponse('获取成功', RBACLib::instance()->getPermissions($pagination['page'], $pagination['page_rows']));
    }

    /**
     * -------------
     *   权限分配管理
     * -------------
     */

    /**
     * 获取角色的权限组列表
     * @return \think\response\Json|\think\response\Jsonp
     * @throws DataValidationException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function roleHoldPermissionGroup()
    {
        $params = $this->apiParams(['role_id']);

        if (empty($params['role_id']) || !is_numeric($params['role_id']) || $params['role_id'] < 0) {
            throw new DataValidationException('请输入正确的角色id');
        }

        $res = RBACLib::instance()->roleHoldPermissionGroup($params['role_id']);

        // 不需要获取code为system的
        $data = [];
        foreach ($res as $r) {
            if (!in_array($r['code'], ['system'])) {
                $data[] = $r;
            }
        }

        return $this->successResponse('获取成功', $data);
    }

    /**
     * 角色更换权限组
     * @return \think\response\Json|\think\response\Jsonp
     * @throws DataValidationException|\Exception
     */
    public function diffPermissionGroup()
    {
        $params = $this->apiParams(['role_id', 'group_code']);

        if (empty($params['role_id']) || !is_numeric($params['role_id']) || $params['role_id'] < 0) {
            throw new DataValidationException('请输入正确的角色id');
        }
        if (empty($params['group_code'])) {
            throw new DataValidationException('请选择需要添加的权限组');
        }

        if (!is_array($params['group_code'])) {
            $params['group_code'] = explode(',', $params['group_code']);
        }

        RBACLib::instance()->diffPermissionGroup($params['role_id'], $params['group_code']);

        return $this->successResponse('操作成功');
    }

    /**
     * -------------
     *    管理员管理
     * -------------
     */

    /**
     * 用户赋予角色
     * @return \think\response\Json|\think\response\Jsonp
     * @throws DataValidationException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function userAssignRoles()
    {
        $params = $this->apiParams(['admin_user_id', 'role_id']);

        if (empty($params['admin_user_id']) || empty($params['role_id'])) {
            throw new DataValidationException('参数不完整');
        }
        if (!empty($this->AdminUser::SUPER_ADMINISTRATOR_ID) && ($params['admin_user_id'] == $this->AdminUser::SUPER_ADMINISTRATOR_ID)) {
            throw new DataValidationException('无权操作');
        }

        if (is_array($params['role_id'])) {
            $role_id = $params['role_id'];
        } else {
            $role_id = explode(',', $params['role_id']);
        }

        $userModel = $this->AdminUser::find($params['admin_user_id']);
        if (empty($userModel)) {
            throw new DataValidationException('用户选择错误');
        }

        RBACLib::instance()->assignRoles($userModel, $role_id);

        return $this->successResponse('授权成功', $params);
    }

    /**
     * 取消用户授权角色
     * @return \think\response\Json|\think\response\Jsonp
     * @throws DataValidationException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \Exception
     */
    public function userCancelRoles()
    {
        $params = $this->apiParams(['admin_user_id', 'role_id']);

        if (empty($params['admin_user_id']) || empty($params['role_id'])) {
            throw new DataValidationException('参数不完整');
        }
        if (!empty($this->AdminUser::SUPER_ADMINISTRATOR_ID) && ($params['admin_user_id'] == $this->AdminUser::SUPER_ADMINISTRATOR_ID)) {
            throw new DataValidationException('无权操作');
        }

        if (is_array($params['role_id'])) {
            $role_id = $params['role_id'];
        } else {
            $role_id = explode(',', $params['role_id']);
        }

        $userModel = $this->AdminUser::find($params['admin_user_id']);
        if (empty($userModel)) {
            throw new DataValidationException('用户选择错误');
        }

        RBACLib::instance()->cancelRoles($userModel, $role_id);

        return $this->successResponse('取消成功');
    }

    /**
     * 同步用户角色
     * @return \think\response\Json|\think\response\Jsonp
     * @throws DataValidationException
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function userSyncRoles()
    {
        $params = $this->apiParams(['admin_user_id', 'role_id']);

        if (empty($params['admin_user_id']) || empty($params['role_id'])) {
            throw new DataValidationException('参数不完整');
        }
        if (!empty($this->AdminUser::SUPER_ADMINISTRATOR_ID) && ($params['admin_user_id'] == $this->AdminUser::SUPER_ADMINISTRATOR_ID)) {
            throw new DataValidationException('无权操作');
        }

        if (is_array($params['role_id'])) {
            $role_id = $params['role_id'];
        } else {
            $role_id = explode(',', $params['role_id']);
        }

        $userModel = $this->AdminUser::find($params['admin_user_id']);
        if (empty($userModel)) {
            throw new DataValidationException('用户选择错误');
        }

        RBACLib::instance()->syncRoles($userModel, $role_id);

        return $this->successResponse('授权成功', $params);
    }

    /**
     * -------------
     *    日志管理
     * -------------
     */

    /**
     * 获取后台日志
     * @return \think\response\Json|\think\response\Jsonp
     * @throws DataValidationException
     */
    public function getLog()
    {
        $params = $this->apiParams(['page', 'page_rows']);

        $pagination = pagination($params);

        return $this->successResponse('获取成功', RBACLib::instance()->getLog($pagination['page'], $pagination['page_rows']));
    }
}