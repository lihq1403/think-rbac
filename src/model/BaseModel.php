<?php


namespace Lihq1403\ThinkRbac\model;


use think\Model;
use think\model\concern\SoftDelete;

/**
 * 基础model
 * Class BaseModel
 * @package Lihq1403\ThinkRbac\model
 */
class BaseModel extends Model
{
    public $autoWriteTimestamp = 'int';

    use SoftDelete;

    protected $hidden = [
        'delete_time'
    ];
}