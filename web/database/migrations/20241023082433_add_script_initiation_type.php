<?php

use think\migration\Migrator;
use think\migration\db\Column;

class AddScriptInitiationType extends Migrator
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
        $table = $this->table('script_initiation');
        $table->addColumn('type', 'string', ['limit' => 16, 'null' => false, 'default' => 'video_script', 'comment' => '脚本类型,video_script:视频脚本, copy:文本脚本', 'after' => 'uid'])
            ->update();
    }
}
