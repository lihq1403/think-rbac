<?php

namespace Lihq1403\ThinkRbac;

use think\Service;

class RBACService extends Service
{
    public function register()
    {
        $this->app->bind('rbac', Rbac::class);
    }

    public function boot()
    {
        $this->commands([
            'lihq1403:rbac-publish' => \Lihq1403\ThinkRbac\command\Publish::class,
            'lihq1403:rbac-migrate' => \Lihq1403\ThinkRbac\command\Migrate::class,
            'lihq1403:rbac-rollback' => \Lihq1403\ThinkRbac\command\Rollback::class,
        ]);
    }
}