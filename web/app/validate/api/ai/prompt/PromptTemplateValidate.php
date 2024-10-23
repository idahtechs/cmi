<?php

namespace app\validate\api\ai\prompt;

use think\Validate;

class PromptTemplateValidate extends Validate
{
    protected $failException = true;

    protected $rule = [
        'prompt|提示词' => 'require',
    ];
}
