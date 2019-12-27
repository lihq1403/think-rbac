<?php

namespace Lihq1403\ThinkRbac\command;

use think\console\Command;
use think\console\Input;
use think\console\Output;
use think\console\input\Option;
use think\facade\Console;
use Lihq1403\ThinkRbac\facade\RBAC;

class RbacRefresh extends Command
{
    protected function configure()
    {
        // 指令配置
        $this->setName('lihq1403:rbac-refresh')->addOption('force', 'f', Option::VALUE_REQUIRED, '强制更新')->setDescription('权限规则刷新，数据会清空');
    }

    protected function execute(Input $input, Output $output)
    {
        if (!$input->hasOption('force') || $input->getOption('force') !== 'yes') {
            $output->writeln('请带上参数，否则视为无效命令');
            return ;
        }

        // 数据库回滚
        Console::call('lihq1403:rbac-rollback');
        $output->writeln('rbac数据库回滚完成');

        // 数据库迁移
        Console::call('lihq1403:rbac-migrate');
        $output->writeln('rbac数据库迁移完成');

        // 增加权限组，已存在的就不添加了
        $permission_group_list = config('rbac.permission_group_list') ?? [];
        foreach ($permission_group_list as $group) {
            try {
                RBAC::addPermissionGroup($group['name'], $group['code'], !empty($group['description']) ? $group['description'] : $group['name']);
            } catch (\Exception $e) {
                $output->writeln('添加权限组“'.$group['code'].'”失败，原因是：'.$e->getMessage());
            }
        }
        $output->writeln('刷权限组完成');

        $permission_list = config('rbac.permission_list') ?? [];
        foreach ($permission_list as $permission) {
            try {
                RBAC::addPermission($permission['name'] ?? '', $permission['controller'] ?? '', $permission['action'] ?? '', $permission['description'] ?? ($permission['name'] ?? ''), $permission['permission_group_code'] ?? '', $permission['behavior'] ?? '', $permission['module'] ?? 'admin');
            } catch (\Exception $e) {
                $output->writeln('添加权限“'.$group['code'].'”失败，原因是：'.$e->getMessage());
            }
        }
        $output->writeln('刷权限完成');

    }
}