<?php

namespace app\common\repositories\ai\tools;


use app\common\dao\ai\tools\ScriptInitiationDao as dao;
use app\common\repositories\ai\ToolsRepository;
use app\common\repositories\BaseRepository;
use think\facade\Db;

class ScriptInitiationRepository extends BaseRepository
{
    /**
     * @var ScriptInitiationDao
     */
    public $dao;

    public function __construct(dao $dao)
    {
        $this->dao = $dao;
    }

    public function initiation($data)
    {
        $toolsRepository = app()->make(ToolsRepository::class);
        $expires = $toolsRepository->validateVIPExpired($data['uid']);
        $integral = 1;
        $remain = $toolsRepository->getRemain($data['uid'], $integral);
        $contentRes = $toolsRepository->rewriteContent($data);
        $returnData = [
            'content' => $contentRes['text'],
            'used' => $integral,
            'remain' => $remain,
            'expires' => $expires,
        ];
        return Db::transaction(function() use ($data, $contentRes, $returnData) {
            $res = $this->dao->create($data);
            $initiation_id = (int) $res[$this->dao->getPk()];

            $scriptRewriteRepository = app()->make(ScriptRewriteRepository::class);
            $version = $scriptRewriteRepository->create([
                'initiation_id' => $initiation_id,
                'original' => $data['original'],
                'prompt' => $data['prompt'],
                'rewrite' => $contentRes['text'],
            ]);

            $returnData['id'] = (int) $version[$scriptRewriteRepository->dao->getPk()];
            $returnData['initiation_id'] = $initiation_id;
            $returnData['createTime'] = $version['create_time'];

            return $returnData;
        });
    }

    public function destroy($id, $uid)
    {
        $exists = $this->dao->existsInitiation($id, $uid);

        return $exists ? $this->dao->update($id, ['is_del' => 1]) : false;
    }

    public function lst(array $where, $page, $limit)
    {
        $query = $this->dao->search($where);
        $count = $query->count();
        $list = $query->page($page, $limit)->select();
        return compact('count', 'list');
    }

    public function detail($id, $uid)
    {
        $exists = $this->dao->existsInitiation($id, $uid, 'versions');

        if($exists) {
            return [
                'id' => $exists[$this->dao->getPk()],
                'content' => $exists['original'],
                'prompt' => $exists['prompt'],
                'updateTime' => $exists['last_update_time'],
                'versions' => $exists['versions'],
            ]; 
        }

        return null;
    }
}
