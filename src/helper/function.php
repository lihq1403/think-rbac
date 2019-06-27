<?php

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