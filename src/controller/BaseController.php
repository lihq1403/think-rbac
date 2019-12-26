<?php

namespace Lihq1403\ThinkRbac\controller;

use Lihq1403\ThinkRbac\protected_traits\ApiRequestTrait;
use Lihq1403\ThinkRbac\protected_traits\ApiResponseTrait;
use think\facade\Db;

class BaseController extends \app\BaseController
{
    use ApiRequestTrait;
    use ApiResponseTrait;

    /**
     * 访问不存在的action
     * @param $method
     * @param $args
     * @return \think\response\Json|\think\response\Jsonp
     */
    public function __call($method, $args)
    {
        return $this->emptyResponse('no found action');
    }

    /**
     * 开启事务
     */
    public static function beginTrans()
    {
        Db::startTrans();
    }

    /**
     * 提交事务
     */
    public static function commitTrans()
    {
        Db::commit();
    }

    /**
     * 关闭事务
     */
    public static function rollbackTrans()
    {
        Db::rollback();
    }
}