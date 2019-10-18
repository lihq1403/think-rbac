<?php

namespace Lihq1403\ThinkRbac\command;

class Rollback extends \think\migration\command\migrate\Rollback
{
    use MigrateTrait;

    protected function configure()
    {
        parent::configure();
        $this->setName('lihq1403:rbac-rollback')->setDescription('数据库迁移回退');
    }
}
