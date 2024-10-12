<?php

namespace app\validate\api\ai\tools;

use think\Validate;

class ExtractCopyValidate extends Validate
{
    protected $failException = true;

    protected $rule = [
        'url|视频链接' => 'require|url',
        'platform|平台' => 'in:douyin,xhs,bilibili,wechat_public_account_article',
    ];

    protected $message = [
        'platform.in' => '仅允许视频平台：抖音，小红书，B站，视频号等',
    ];
}
