<?php

namespace Lihq1403\ThinkRbac\service;

use Lihq1403\ThinkRbac\helper\PageHelper;
use Lihq1403\ThinkRbac\model\Log;
use Lihq1403\ThinkRbac\protected_traits\Singleton;
use think\facade\Request;

class LogService
{
    use Singleton;

    /**
     * 添加日志记录
     * @param $user_id
     * @return Log
     */
    public function add(int $user_id)
    {
        $data = [
            'user_id' => $user_id,
            'method' => strtoupper(Request::method()),
            'path' => Request::rule()->getName(),
            'ip' => get_client_ip(),
            'input' => Request::param(),
        ];

        return Log::create($data);
    }

    /**
     * 获取日志列表
     * @param $page
     * @param $page_rows
     * @param array $user_field
     * @return array
     */
    public function getList(int $page, int $page_rows, array $user_field)
    {
        $pageHelper = new PageHelper(new Log());
        $order = 'create_time desc';
        $with = [
            'user' => function($query) use ($user_field){
                if (empty($user_field)) {
                    $user_field= ['id', 'username'];
                }
                $query->field($user_field);
            }
        ];
        $field = ['id', 'user_id', 'method', 'path', 'ip', 'input', 'create_time'];
        return $pageHelper->with($with)->order($order)->setFields($field)->page($page)->pageRows($page_rows)->result();
    }
}