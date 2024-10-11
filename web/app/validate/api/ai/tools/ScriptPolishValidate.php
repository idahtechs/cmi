<?php

namespace app\validate\api\ai\tools;

use think\Validate;

class ScriptPolishValidate extends Validate
{
    protected $failException = true;

    protected $rule = [
        'prompt|提示词' => 'require',
    ];
}
