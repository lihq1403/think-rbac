<?php

namespace Lihq1403\ThinkRbac\protected_traits;

use Lihq1403\ThinkRbac\service\LogService;

trait LogOperation
{
    /**
     * 添加日志
     * @param $user_id
     * @return bool
     */
    public function log($user_id)
    {
        LogService::instance()->add($user_id);
        return true;
    }

    /**
     * 获取日志列表
     * @param $page
     * @param $page_rows
     * @param array $user_field
     * @return array
     */
    public function getLogList($page, $page_rows, $user_field = [])
    {
        return LogService::instance()->getList($page, $page_rows, $user_field);
    }
}