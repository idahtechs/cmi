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
        // TODO: 重新生成暂不扣积分
        $integral = 0;

        $remain = $toolsRepository->getRemain($data['uid'], $integral);

        $contentRes = $toolsRepository->rewriteContent($data, 'REWRITE');

        $returnData = [
            'content' => $contentRes['text'],
            'used' => $integral,
            'remain' => $remain,
            'expires' => $expires,
        ];

        $res = Db::transaction(function () use ($data, $existsInitiation, $contentRes, $returnData, $scriptInitiationRepository) {
            $initiation_id = (int) $existsInitiation['initiation_id'];
            $createTime = date('Y-m-d H:i:s');
            $create = $this->dao->create([
                'initiation_id' => $initiation_id,
                'uid' => $data['uid'],
                'original' => $data['original'],
                'prompt' => $data['prompt'],
                'rewrite' => $contentRes['text'],
                'method' => 'recreate',
                'create_time' => $createTime,
            ]);

            $scriptInitiationRepository->update($existsInitiation['initiation_id'], [
                'original' => $data['original'],
                'prompt' => $data['prompt'],
                'last_update_time' => $create['create_time']
            ]);

            $returnData['id'] = (int) $create[$this->dao->getPk()];
            $returnData['initiation_id'] = $initiation_id;
            $returnData['createTime'] = $createTime;

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
        // TODO: 润色暂不扣积分
        $integral = 0;

        $remain = $toolsRepository->getRemain($data['uid'], $integral);

        $contentRes = $toolsRepository->rewriteContent([
            'original' => $exists['rewrite'],
            'prompt' => $data['prompt'],
        ], 'POLISH');

        $returnData = [
            'content' => $contentRes['text'],
            'used' => $integral,
            'remain' => $remain,
            'expires' => $expires,
        ];

        $res = Db::transaction(function () use ($data, $exists, $contentRes, $returnData, $scriptInitiationRepository) {
            $initiation_id = (int) $exists['initiation_id'];
            $create = $this->dao->create([
                'initiation_id' => $initiation_id,
                'uid' => $data['uid'],
                'original' => $exists['rewrite'],
                'prompt' => $data['prompt'],
                'rewrite' => $contentRes['text'],
                'method' => 'polish',
            ]);

            $scriptInitiationRepository->update($exists['initiation_id'], ['last_update_time' => $create['create_time']]);

            $returnData['id'] = (int) $create[$this->dao->getPk()];
            $returnData['initiation_id'] = $initiation_id;
            $returnData['createTime'] = $create['create_time'];

            return $returnData;
        });

        return $res;
    }
}
