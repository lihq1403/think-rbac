<?php

if ('cli' === PHP_SAPI || 'phpdbg' ===PHP_SAPI) {
    \think\Console::addDefaultCommands([
        'lihq1403:rbac-publish' => \Lihq1403\ThinkRbac\command\Publish::class,
        'lihq1403:rbac-migrate' => \Lihq1403\ThinkRbac\command\Migrate::class,
        'lihq1403:rbac-rollback' => \Lihq1403\ThinkRbac\command\Rollback::class,
    ]);
}

