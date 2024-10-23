<?php

namespace app\common\model\ai\prompt;


use app\common\model\BaseModel;

class PromptTemplate extends BaseModel
{
    public static function tablePk(): string
    {
        return 'prompt_template_id';
    }

    public static function tableName(): string
    {
        return 'prompt_template';
    }
}
