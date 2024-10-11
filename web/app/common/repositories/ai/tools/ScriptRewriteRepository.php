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

    public function destroy($id, $uid)
    {
        $res = $this->detail($id, $uid);

        return $res ? $this->dao->update($id, ['is_del' => 1]) : false;
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

    public function polish($data)
    {
        $res = $this->detail($data['rewrite_id'], $data['uid']);

        if (!$res) {
            return null;
        }

        $toolsRepository = app()->make(ToolsRepository::class);

        $expires = $toolsRepository->validateVIPExpired($data['uid']);

        $integral = 1;

        $remain = $toolsRepository->getRemain($data['uid'], $integral);

        $contentRes = $toolsRepository->rewriteContent([
            'original' => $res['original'],
            'prompt' => $data['prompt'],
        ]);
        
        $contentRes = ['text' => 123];

        $create = $this->dao->create([
            'uid' => $data['uid'],
            'original' => $res['original'],
            'prompt' => $data['prompt'],
            'rewrite' => $contentRes['text'],
        ]);

        $res = [
            'id' => $create[$this->dao->getPk()],
            'content' => $create['rewrite'],
            'used' => $integral,
            'remain' => $remain,
            'expires' => $expires,
        ];

        return $res;
    }

    public function detail($id, $uid)
    {
        $res = $this->dao->get($id);
        return $res;

        if($res && $res['is_del'] == 0 && $res['uid'] != $uid) {
            return $res;
        }

        return null;
    }
}
