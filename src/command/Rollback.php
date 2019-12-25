<?php

namespace Lihq1403\ThinkRbac\command;

use Phinx\Migration\MigrationInterface;
use think\console\Input;
use think\console\Output;

class Rollback extends \think\migration\command\migrate\Rollback
{
    use MigrateTrait;

    protected function configure()
    {
        parent::configure();
        $this->setName('lihq1403:rbac-rollback')->setDescription('rbac数据库迁移回退');
    }

    /**
     * @param Input $input
     * @param Output $output
     * @throws \Exception
     */
    protected function execute(Input $input, Output $output)
    {
        $start = microtime(true);

        $migrations = $this->getMigrations();

        foreach ($migrations as $migration) {
            $this->executeMigration($migration, MigrationInterface::DOWN);
        }

        $end = microtime(true);

        $output->writeln('');
        $output->writeln('<comment>All Done. Took ' . sprintf('%.4fs', $end - $start) . '</comment>');
    }
}
