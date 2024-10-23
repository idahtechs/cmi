<?php

use think\migration\Migrator;
use think\migration\db\Column;

class CreatePromptTemplate extends Migrator
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change()
    {
        $table = $this->table('prompt_template', ['id' => 'prompt_template_id', 'comment' => '提示词模板']);

        $table
            ->addColumn('uid', 'integer', ['limit' => 11, 'signed' => false, 'null' => false, 'default' => 0, 'comment' => '用户id'])
            ->addColumn('admin_id', 'integer', ['limit' => 11, 'signed' => false, 'null' => false, 'default' => 0, 'comment' => '管理员id'])
            ->addColumn('type', 'string', ['null' => false, 'default' => '', 'comment' => '模板类型,空值为全部,initiation:仿写文案,polish:润色'])
            ->addColumn('title', 'string', ['limit' => 255, 'default' => '', 'comment' => '模板标题'])
            ->addColumn('prompt', 'text', ['null' => false, 'comment' => '模板内容'])
            ->addColumn('sort', 'integer', ['limit' => 11, 'default' => 0, 'comment' => '排序'])
            ->addColumn('status', 'boolean', ['null' => false, 'default' => 1, 'comment' => '状态'])
            ->addColumn('is_del', 'boolean', ['null' => false, 'default' => 0, 'comment' => '是否删除'])
            ->addColumn('create_time', 'timestamp', ['null' => false, 'default' => 'CURRENT_TIMESTAMP', 'comment' => '创建时间'])
            ->addColumn('update_time', 'timestamp', ['null' => false, 'default' => 'CURRENT_TIMESTAMP', 'update' => 'CURRENT_TIMESTAMP', 'comment' => '更新时间'])
            ->addIndex('uid')
            ->addIndex('admin_id')
            ->create();
    }
}
