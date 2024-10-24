<?php

use think\migration\Migrator;
use think\migration\db\Column;

class CreateScriptRewrite extends Migrator
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
        $table = $this->table('script_rewrite', ['id' => 'rewrite_id', 'comment' => '脚本生成记录表']);

        $table
        ->addColumn('initiation_id', 'integer', ['limit' => 11, 'signed' => false, 'null' => false, 'comment' => '仿写文案id'])
        ->addColumn('original', 'text', ['null' => false, 'comment' => '用户提交的脚本内容'])
        ->addColumn('prompt', 'text', ['null' => false, 'comment' => '用户提交的提示词'])
        ->addColumn('rewrite', 'text', ['null' => false, 'comment' => '生成的脚本内容'])
        ->addColumn('method', 'string', ['null' => false, 'default' => 'create', 'comment' => '生成方式，create: 首次生成，polish: 润色，recreate: 重新生成'])
        ->addColumn('is_del', 'boolean', ['null' => false, 'default' => 0, 'comment' => '是否删除'])
        ->addColumn('create_time', 'timestamp', ['null' => false, 'default' => 'CURRENT_TIMESTAMP', 'comment' => '创建时间'])
        ->addIndex('initiation_id')
        ->create();
    }
}
