<?php


namespace Lihq1403\ThinkRbac\model;


use think\Model;

/**
 * 基础model
 * Class BaseModel
 * @package Lihq1403\ThinkRbac\model
 */
class BaseModel extends Model
{
    public $autoWriteTimestamp = 'int';

    public function saveData($data = [])
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

}