<?php

namespace Lihq1403\ThinkRbac\protected_traits;

use Lihq1403\ThinkRbac\exception\DataValidationException;
use think\facade\Request;

trait ApiRequestTrait
{
    /**
     * api 接口专用参数处理
     * @param array $only
     * @param array $must
     * @param string $type
     * @param bool $trim
     * @return array|mixed
     * @throws DataValidationException
     */
    protected function apiParams(array $only = [], array $must = [], string $type = 'param', bool $trim = true)
    {
        $params = Request::only($only, $type);

        if ($trim) {
            // 参数去空
            $params = array_map_function($params, 'trim');
        }

        if (!empty($must)) {
            foreach ($must as $item) {
                if (empty($params[$item])) {
                    throw new DataValidationException($item . ' ' . lang('require'));
                }
            }
        }
        return $params;
    }
}