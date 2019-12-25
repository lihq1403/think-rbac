<?php

namespace Lihq1403\ThinkRbac\command;

use think\migration\command\migrate\Run;

class Migrate extends Run
{
    use MigrateTrait;

    protected function configure()
    {
        parent::configure();
        $this->setName('lihq1403:rbac-migrate')->setDescription('rbac数据库迁移');
    }
}