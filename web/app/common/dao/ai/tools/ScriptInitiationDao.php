<?php

namespace app\common\dao\ai\tools;

use app\common\dao\BaseDao;
use app\common\model\ai\tools\ScriptInitiation as model;

class ScriptInitiationDao extends BaseDao
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
        $alias = 'SI';

        return ($this->getModel())::getDB()->alias($alias)
        ->when(isset($where['is_del']) && $where['is_del'] !== '', function ($query) use ($where, $alias) {
            $query->where($alias . '.is_del', $where['is_del']);
        })->when(isset($where['uid']) && $where['uid'] !== '', function ($query) use ($where, $alias) {
            $query->where($alias . '.uid', $where['uid']);
        })->leftJoin(
            'ExtractCopy EC','EC.extract_copy_id = ' . $alias . '.extract_copy_id'
        )->order(
            $alias . '.last_update_time DESC'
        )->field(
            $this->getPk() . ' as id, ' . $alias . '.type as type, ' . $alias . '.original as content, ' . $alias . '.prompt as prompt, EC.platform as source, ' . $alias . '.last_update_time as updateTime'
        );
    }

    public function existsInitiation(int $id, int $uid, string $with = null)
    {
        $res = ($this->getModel())::getDB()
        ->when(!!$with == true, function ($query) use ($with) {
            $query->with($with);
        })->find($id);

        if($res && $res['is_del'] == 0 && $res['uid'] == $uid) {
            return $res;
        }

        return null;
    }
}
