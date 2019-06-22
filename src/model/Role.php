<?php

namespace Lihq1403\ThinkRbac\model;


use Lihq1403\ThinkRbac\helper\PageHelper;

/**
 * 角色表
 * Class Role
 * @package Lihq1403\ThinkRbac\model
 */
class Role extends BaseModel
{
    public $name = 'lihq1403_role';

    /**
     * 保存角色
     * @param array $data
     * @return $this
     */
    public function saveRole($data = [])
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
     * 删除角色
     * @param $role_id
     * @return bool
     */
    public function delRole($role_id)
    {
        return self::destroy($role_id);
    }

    /**
     * 获取所有角色分页列表
     * @param $map
     * @param $field
     * @param int $page
     * @param int $page_rows
     * @return array
     */
    public function getList($map, $field, $page = 1, $page_rows = 10)
    {
        return (new PageHelper(new self()))->where($map)->setFields($field)->page($page)->pageRows($page_rows)->result();
    }
}