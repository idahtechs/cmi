<?php

namespace app\common\dao\ai\prompt;

use app\common\dao\BaseDao;
use app\common\model\ai\prompt\PromptTemplate as model;

class PromptTemplateDao extends BaseDao
{
    protected function getModel(): string
    {
        return model::class;
    }

    protected function getDB()
    {
        return ($this->getModel())::getDB();
    }

    public function valid_data($id, $uid = 0)
    {
        return $this->getWhere([
            $this->getPk() => $id,
            'status' => 1,
            'is_del' => 0,
            'uid' => $uid,
        ]);
    }

    public function search(array $where)
    {
        return $this->getDB()
        ->when(isset($where['is_del']) && $where['is_del'] !== '', function ($query) use ($where) {
            $query->where('is_del', $where['is_del']);
        })->when(isset($where['uid']) && $where['uid'] !== '', function ($query) use ($where) {
            $query->where('uid', $where['uid']);
        })->when(isset($where['uids']) && !empty($where['uids']), function ($query) use ($where) {
            $uids = is_array($where['uids']) ? implode(',', $where['uids']) : $where['uids'];
            $query->where('uid', 'in', $uids);
        })->when(isset($where['status']) && $where['status']!== '', function ($query) use ($where) {
            $query->where('status', $where['status']);
        })
        ->order('sort ASC, create_time DESC');
    }
}
