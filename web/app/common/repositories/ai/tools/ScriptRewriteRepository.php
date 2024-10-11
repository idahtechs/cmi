<?php

namespace app\common\repositories\ai\tools;


use app\common\dao\ai\tools\ScriptRewriteDao as dao;
use app\common\repositories\ai\ToolsRepository;
use app\common\repositories\BaseRepository;

class ScriptRewriteRepository extends BaseRepository
{
    /**
     * @var ScriptRewriteDao
     */
    public $dao;

    public function __construct(dao $dao)
    {
        $this->dao = $dao;
    }

    public function rewrite($data)
    {
        $toolsRepository = app()->make(ToolsRepository::class);

        $expires = $toolsRepository->validateVIPExpired($data['uid']);

        $integral = 1;

        $remain = $toolsRepository->getRemain($data['uid'], $integral);

        $contentRes = $toolsRepository->rewriteContent($data);
        $data['rewrite'] = $contentRes['text'];

        $data = $this->dao->create($data);

        $res = [
            'id' => $data[$this->dao->getPk()],
            'content' => $data['rewrite'],
            'used' => $integral,
            'remain' => $remain,
            'expires' => $expires,
        ];

        return $res;
    }

    public function destroy($id)
    {
        return $this->dao->update($id, ['is_del' => 1]);
    }

    public function lst(array $where, $page, $limit)
    {
        $query = $this->dao->search($where)
            ->field('rewrite_id as id, rewrite as content, prompt, create_time as createTime')
            ->order('create_time DESC');
        $count = $query->count();
        $list = $query->page($page, $limit)->select();
        return compact('count', 'list');
    }
}
