<?php

namespace Lihq1403\ThinkRbac\model;

class Log extends BaseModel
{
    public $name = 'rbac_log';

    public $json = [
        'input'
    ];

    public $auto = [
        'ip'
    ];

    public function user()
    {
        return $this->belongsTo(config('rbac.user_model'), 'user_id', 'id');
    }

    public function setIpAttr($ip)
    {
        if (empty($ip)) {
            return 0;
        }
        return ip2long($ip);
    }

    public function getIpAttr($ip)
    {
        if (empty($ip)) {
            return '';
        }
        return long2ip($ip);
    }
}