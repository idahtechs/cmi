<?php

namespace app\common\model\ai\tools;


use app\common\model\BaseModel;

class ExtractCopy extends BaseModel
{
    public static function tablePk(): string
    {
        return 'copy_id';
    }

    public static function tableName(): string
    {
        return 'extract_copy';
    }
}
