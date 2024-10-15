<?php

use think\migration\Migrator;
use think\migration\db\Column;

class CreateScriptImitation extends Migrator
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
        $table = $this->table('script_initiation', ['id' => 'initiation_id', 'comment' => '脚本仿写表']);

        $table
        ->addColumn('extract_copy_id', 'integer', ['limit' => 11, 'signed' => false, 'null' => false, 'comment' => '提取记录id'])
        ->addColumn('uid', 'integer', ['limit' => 11, 'signed' => false, 'null' => false, 'comment' => '用户id'])
        ->addColumn('original', 'text', ['null' => false, 'comment' => '用户提交的脚本内容'])
        ->addColumn('prompt', 'text', ['null' => false, 'comment' => '用户提交的提示词'])
        ->addColumn('is_del', 'boolean', ['null' => false, 'default' => 0, 'comment' => '是否删除'])
        ->addColumn('create_time', 'timestamp', ['null' => false, 'default' => 'CURRENT_TIMESTAMP', 'comment' => '仿写时间'])
        ->addColumn('last_update_time', 'timestamp', ['null' => false, 'default' => 'CURRENT_TIMESTAMP', 'update' => 'CURRENT_TIMESTAMP', 'comment' => '最后更新时间'])
        ->addIndex('extract_copy_id')
        ->addIndex('uid')
        ->create();
    }
}
