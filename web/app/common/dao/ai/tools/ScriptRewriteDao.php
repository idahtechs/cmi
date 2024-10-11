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

    /**
     * @param array $where
     * @return \think\db\BaseQuery
     * @author xaboy
     * @day 2020/9/18
     */
    public function search(array $where)
    {
        return ($this->getModel())::getDB()->when(isset($where['is_del']) && $where['is_del'] !== '', function ($query) use ($where) {
            $query->where('is_del', $where['is_del']);
        })->when(isset($where['uid']) && $where['uid'] !== '', function ($query) use ($where) {
            $query->where('uid', $where['uid']);
        });
    }
}
