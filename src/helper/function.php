<?php

if (!function_exists('pagination')) {
    /**
     * 获取分页配置
     * @param $params
     * @return array
     */
    function pagination($params)
    {
        if (empty($params['page']) || !is_numeric($params['page']) || $params['page'] <= 0) {
            $page = 1;
        } else {
            $page = (int)$params['page'];
        }
        if (empty($params['page_rows']) || !is_numeric($params['page_rows']) || $params['page_rows'] <= 0) {
            $page_rows = 10;
        } else {
            $page_rows = (int)$params['page_rows'];
        }
        return compact('page', 'page_rows');
    }
}


if (!function_exists('array_map_function')) {
    /**
     * 数组递归格式化
     * @param $array
     * @param string|array $function
     * @return mixed
     */
    function array_map_function($array, $function)
    {
        foreach ($array as &$item) {
            if (is_array($item)) {
                $item = array_map_function($item, $function);
            } else {
                if (is_array($function)) {
                    foreach ($function as $func) {
                        $item = $func($item);
                    }
                } else {
                    $item = $function($item);
                }
            }
        }
        return $array;
    }
}


if (!function_exists('array_del_empty')) {
    /**
     * 数组去空
     * @param $arr
     * @param callable $function
     * @return array
     */
    function array_del_empty($arr, callable $function = null)
    {
        if (empty($function)) {
            $function = function ($var) {
                if($var === '' || $var === null)
                {
                    return false;
                }
                return true;
            };
        }
        // 去掉空字符串数据
        return array_filter($arr, $function);
    }
}


if (!function_exists('get_client_ip')) {
    /**
     * 获取真实ip
     * @return array|false|string
     */
    function get_client_ip()
    {
        //判断服务器是否允许$_SERVER
        if(isset($_SERVER)){
            if(isset($_SERVER['HTTP_X_FORWARDED_FOR'])){
                $real_ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
            }elseif(isset($_SERVER['HTTP_CLIENT_IP'])) {
                $real_ip = $_SERVER['HTTP_CLIENT_IP'];
            }else{
                $real_ip = $_SERVER['REMOTE_ADDR'];
            }
        }else{
            //不允许就使用getenv获取
            if(getenv("HTTP_X_FORWARDED_FOR")){
                $real_ip = getenv( "HTTP_X_FORWARDED_FOR");
            }elseif(getenv("HTTP_CLIENT_IP")) {
                $real_ip = getenv("HTTP_CLIENT_IP");
            }else{
                $real_ip = getenv("REMOTE_ADDR");
            }
        }
        return $real_ip;
    }
}

