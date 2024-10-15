<?php

namespace app\common\repositories\ai\tools;


use app\common\dao\ai\tools\ScriptRewriteDao as dao;
use app\common\repositories\ai\ToolsRepository;
use app\common\repositories\BaseRepository;
use think\facade\Db;

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

    public function recreate($id, $data)
    {
        $scriptInitiationRepository = app()->make(ScriptInitiationRepository::class);
        $existsInitiation = $scriptInitiationRepository->existsInitiation($id, $data['uid']);

        if (!$existsInitiation) {
            return null;
        }
        
        $toolsRepository = app()->make(ToolsRepository::class);

        $expires = $toolsRepository->validateVIPExpired($data['uid']);

        $integral = 1;

        $remain = $toolsRepository->getRemain($data['uid'], $integral);

        $contentRes = $toolsRepository->rewriteContent($data, 'REWRITE');

        $returnData = [
            'content' => $contentRes['text'],
            'used' => $integral,
            'remain' => $remain,
            'expires' => $expires,
        ];

        $res = Db::transaction(function () use ($data, $existsInitiation, $contentRes, $returnData, $scriptInitiationRepository) {
            $create = $this->dao->create([
                'initiation_id' => $existsInitiation['initiation_id'],
                'uid' => $data['uid'],
                'original' => $data['original'],
                'prompt' => $data['prompt'],
                'rewrite' => $contentRes['text'],
                'method' => 'recreate',
            ]);

            $scriptInitiationRepository->update($existsInitiation['initiation_id'], ['last_update_time' => $create['create_time']]);

            $returnData['id'] = (int) $create[$this->dao->getPk()];

            return $returnData;
        });

        return $res;
    }

    public function destroy($id, $uid)
    {
        $exists = $this->dao->existsRewrite($id, $uid);

        return $exists ? $this->dao->update($id, ['is_del' => 1]) : false;
    }

    public function polish($id, $data)
    {
        $exists = $this->dao->existsRewrite($id, $data['uid']);
        
        if (!$exists) {
            return null;
        }

        $scriptInitiationRepository = app()->make(ScriptInitiationRepository::class);
        $existsInitiation = $scriptInitiationRepository->existsInitiation($exists['initiation_id'], $data['uid']);

        if (!$existsInitiation) {
            return null;
        }

        $toolsRepository = app()->make(ToolsRepository::class);

        $expires = $toolsRepository->validateVIPExpired($data['uid']);

        $integral = 1;

        $remain = $toolsRepository->getRemain($data['uid'], $integral);

        $contentRes = $toolsRepository->rewriteContent([
            'original' => $exists['original'],
            'prompt' => $data['prompt'],
        ], 'POLISH');

        $returnData = [
            'content' => $contentRes['text'],
            'used' => $integral,
            'remain' => $remain,
            'expires' => $expires,
        ];

        $res = Db::transaction(function () use ($data, $exists, $contentRes, $returnData, $scriptInitiationRepository) {
            $create = $this->dao->create([
                'initiation_id' => $exists['initiation_id'],
                'uid' => $data['uid'],
                'original' => $exists['original'],
                'prompt' => $data['prompt'],
                'rewrite' => $contentRes['text'],
                'method' => 'polish',
            ]);

            $scriptInitiationRepository->update($exists['initiation_id'], ['last_update_time' => $create['create_time']]);

            $returnData['id'] = (int) $create[$this->dao->getPk()];

            return $returnData;
        });

        return $res;
    }
}
