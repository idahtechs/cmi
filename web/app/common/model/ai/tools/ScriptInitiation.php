<?php

namespace app\common\model\ai\tools;


use app\common\model\BaseModel;

class ScriptInitiation extends BaseModel
{
    public static function tablePk(): string
    {
        return 'initiation_id';
    }

    public static function tableName(): string
    {
        return 'script_initiation';
    }

    public function versions()
    {
        return $this->hasMany(ScriptRewrite::class, 'initiation_id', 'initiation_id')
        ->where('is_del', 0)
        ->field('rewrite_id as id, method, rewrite as content, prompt, original, create_time as createTime, initiation_id')
        ->order('create_time DESC');
    }
}
