<?php

namespace app\validate\api\ai\tools;

use think\Validate;

class ScriptRewriteValidate extends Validate
{
    protected $failException = true;

    protected $rule = [
        'original|脚本内容' => 'require',
        'prompt|提示词' => 'require',
    ];
}
