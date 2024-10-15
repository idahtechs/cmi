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
        return ($this->getModel())::getDB()->alias('SR')
        ->when(isset($where['is_del']) && $where['is_del'] !== '', function ($query) use ($where) {
            $query->where('is_del', $where['is_del']);
        })->when(isset($where['uid']) && $where['uid'] !== '', function ($query) use ($where) {
            $query->where('SR.uid', $where['uid']);
        })->leftJoin(
            'ExtractCopy EC','EC.extract_copy_id = SR.extract_copy_id'
        )->order(
            'SR.create_time DESC'
        )->field(
            $this->getPk() . ' as id, SR.original as content, SR.prompt as prompt, EC.platform as source, SR.create_time as updateTime'
        );
    }

    public function existsRewrite($id, $uid)
    {
        $res = $this->get($id);
        return $res;

        if($res && $res['is_del'] == 0 && $res['uid'] == $uid) {
            return $res;
        }

        return null;
    }
}
