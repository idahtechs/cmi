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
        $exists = $this->exists($id, $uid);

        return $exists ? $this->dao->update($id, ['is_del' => 1]) : false;
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
        $exists = $this->exists($data['rewrite_id'], $data['uid']);

        if (!$exists) {
            return null;
        }

        $toolsRepository = app()->make(ToolsRepository::class);

        $expires = $toolsRepository->validateVIPExpired($data['uid']);

        $integral = 1;

        $remain = $toolsRepository->getRemain($data['uid'], $integral);

        $contentRes = $toolsRepository->rewriteContent([
            'original' => $exists['original'],
            'prompt' => $data['prompt'],
        ], 'polish');

        $create = $this->dao->create([
            'uid' => $data['uid'],
            'original' => $exists['original'],
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

    public function exists($id, $uid)
    {
        $res = $this->dao->get($id);

        if($res && $res['is_del'] == 0 && $res['uid'] == $uid) {
            return $res;
        }

        return null;
    }

    public function detail($id, $uid)
    {
        $exists = $this->exists($id, $uid);

        if($exists) {
           return [
            'id' => $exists[$this->dao->getPk()],
            'content' => $exists['rewrite'],
            'prompt' => $exists['prompt'],
            'createTime' => $exists['create_time'],
           ]; 
        }

        return null;
    }
}