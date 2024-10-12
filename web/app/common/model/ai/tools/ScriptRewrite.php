<?php

namespace app\common\model\ai\tools;


use app\common\model\BaseModel;

class ScriptRewrite extends BaseModel
{
    public static function tablePk(): string
    {
        return 'rewrite_id';
    }

    public static function tableName(): string
    {
        return 'script_rewrite';
    }
}
