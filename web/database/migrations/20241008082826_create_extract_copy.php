<?php

use think\migration\Migrator;
use think\migration\db\Column;

class CreateExtractCopy extends Migrator
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
        $table = $this->table('extract_copy', ['id' => 'copy_id', 'comment' => '提取文案表']);

        $table
        ->addColumn('uid', 'integer', ['limit' => 11, 'signed' => false, 'null' => false, 'comment' => '用户id'])
        ->addColumn('platform', 'string', ['limit' => 16, 'null' => false, 'comment' => '视频平台'])
        ->addColumn('url', 'string', ['limit' => 255, 'null' => false, 'comment' => '视频链接'])
        ->addColumn('content', 'text', ['null' => false, 'comment' => '文案内容'])
        ->addColumn('integral', 'integer', ['limit' => 11, 'signed' => false, 'null' => false, 'comment' => '扣减积分'])
        ->addColumn('create_time', 'timestamp', ['null' => false, 'default' => 'CURRENT_TIMESTAMP', 'comment' => '创建时间'])
        ->addIndex('uid')
        ->create();
    }
}
