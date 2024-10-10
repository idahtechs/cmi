<?php

namespace app\common\dao\ai\tools;

use app\common\dao\BaseDao;
use app\common\model\ai\tools\ScriptRewrite as model;

class ScriptRewriteDao extends BaseDao
{
    protected function getModel(): string
    {
        return model::class;
    }
}
