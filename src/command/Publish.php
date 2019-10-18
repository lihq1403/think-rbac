<?php

namespace Lihq1403\ThinkRbac\command;

use think\console\Command;
use think\console\Input;
use think\console\Output;

class Publish extends Command
{
    protected function configure()
    {
        $this->setName('lihq1403:rbac-publish')->setDescription('发布配置文件');
    }

    protected function execute(Input $input, Output $output)
    {
        $file_path = app()->getConfigPath(). 'rbac.php';
        if (!file_exists($file_path)) {
            copy(__DIR__ . '/../config/rbac.php', $file_path);
            $output->writeln('发布rbac配置成功');
        } else {
            $output->writeln('rbac配置文件已存在');
        }
    }
}