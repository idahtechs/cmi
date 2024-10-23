<?php

namespace app\common\repositories\ai\prompt;

use app\common\dao\ai\prompt\PromptTemplateDao as dao;
use app\common\repositories\BaseRepository;
use think\exception\ValidateException;
use think\facade\Log;

class PromptTemplateRepository extends BaseRepository
{
    /**
     * @var dao
     */
    public $dao;

    public function __construct(dao $dao)
    {
        $this->dao = $dao;
    }

    public function create(array $data)
    {
        $data['create_time'] = date('Y-m-d H:i:s');
        $data['update_time'] = $data['create_time'];

        return $this->dao->create($data);
    }

    public function delete($id, $uid)
    {
        $data = $this->dao->valid_data($id, $uid);

        if (empty($data)) {
            throw new ValidateException('提示词模板不存在');
        }

        $res = $this->dao->update($id, ['is_del' => 1, 'update_time' => date('Y-m-d H:i:s')]);

        if (!$res) {
            throw new ValidateException('删除失败');
        }

        return $res;
    }

    public function update($id, $uid, array $data)
    {
        $res = $this->dao->valid_data($id, $uid);

        if (empty($res)) {
            throw new ValidateException('提示词模板不存在');
        }

        $this->dao->update($id, $data);

        $res = $this->dao->valid_data($id, $uid);

        return $res;
    }

    public function detail($id, $uid)
    {
        $res = $this->dao->valid_data($id, $uid);

        if (empty($res)) {
            throw new ValidateException('提示词模板不存在');
        }

        return $res;
    }

    public function lst($where, $page, $limit, $hidden = [])
    {
        $query = $this->dao->search($where)->hidden($hidden);
        $count = $query->count();
        $list = $query->page($page, $limit)->select();

        return compact('count', 'list');
    }

    public function count($where) {
        return $this->dao->search($where)->count();
    }

    public function userCustomLimit($isSVip) {
        return intval(systemConfig($isSVip ? 'svip_prompt_template_limit' : 'prompt_template_limit'));
    }
}
