<?php

use Phinx\Db\Adapter\MysqlAdapter;
use think\migration\Migrator;
use think\migration\db\Column;

class Rbac extends Migrator
{
    public $role_table = 'rbac_role';
    public $user_role_table = 'rbac_user_role';
    public $permission_table = 'rbac_permission';
    public $role_permission_table = 'rbac_role_permission';

    public function up()
    {
        $table = $this->table($this->role_table,['comment'=>'角色表']);
        $table->addColumn('name', 'string', ['limit' => 20, 'default'=>'', 'comment' => '角色名称'])
            ->addColumn('description', 'string', ['null' => true, 'comment' => '角色描述'])
            ->addColumn('create_time', 'integer', ['default' => 0, 'comment' => '创建时间', 'null' => false])
            ->addColumn('update_time', 'integer', ['default' => 0, 'comment' => '更新时间', 'null' => false])
            ->addColumn('status', 'integer', ['limit' => \Phinx\Db\Adapter\MysqlAdapter::INT_TINY, 'default' => 1, 'comment' => '状态 1、开启 0、禁用', 'null' => false])
            ->addIndex(['name'], ['unique' => true])
            ->save();

        $table = $this->table($this->user_role_table,['comment'=>'用户角色表']);
        $table->addColumn('user_id', 'integer', ['signed' => true, 'comment' => '关联用户id'])
            ->addColumn('role_id', 'integer', ['signed' => true, 'comment' => '关联角色id'])
            ->addColumn('create_time', 'integer', ['default' => 0, 'comment' => '创建时间', 'null' => false])
            ->addColumn('update_time', 'integer', ['default' => 0, 'comment' => '更新时间', 'null' => false])
            ->addIndex(['admin_user_id','role_id'])
            ->save();

        $table = $this->table($this->permission_table,['comment'=>'权限表']);
        $table->addColumn('name', 'string', ['default'=>'', 'comment' => '权限名称'])
            ->addColumn('description', 'string', ['null' => true, 'comment' => '权限描述'])
            ->addColumn('module', 'string', ['default'=>'', 'comment'=>'访问module'])
            ->addColumn('controller', 'string', ['default'=>'', 'comment'=>'访问controller'])
            ->addColumn('action', 'string', ['default'=>'', 'comment'=>'访问action'])
            ->addColumn('behavior', 'string', ['default'=>'', 'comment'=>'操作行为 list, add, edit, show, delete, import, export, download'])
            ->addColumn('group', 'string', ['default'=>'', 'comment'=>'所属组类别，系统自定义'])
            ->addColumn('create_time', 'integer', ['default' => 0, 'comment' => '创建时间', 'null' => false])
            ->addColumn('update_time', 'integer', ['default' => 0, 'comment' => '更新时间', 'null' => false])
            ->addIndex(['name'], ['unique' => true])
            ->save();

        $table = $this->table($this->role_permission_table,['comment'=>'角色权限表']);
        $table->addColumn('role_id', 'integer', ['signed' => true, 'comment' => '关联角色id'])
            ->addColumn('permission_id', 'integer', ['signed' => true, 'comment' => '关联权限id'])
            ->addColumn('create_time', 'integer', ['default' => 0, 'comment' => '创建时间', 'null' => false])
            ->addColumn('update_time', 'integer', ['default' => 0, 'comment' => '更新时间', 'null' => false])
            ->addIndex(['permission_id','role_id'])
            ->save();
    }

    public function down()
    {
        $this->dropTable($this->role_table);
        $this->dropTable($this->user_role_table);
        $this->dropTable($this->permission_table);
        $this->dropTable($this->role_permission_table);
    }
}