<?php

namespace app\common\repositories\ai\tools;


use app\common\dao\ai\tools\ScriptRewriteDao as dao;
use app\common\repositories\ai\ToolsRepository;
use app\common\repositories\BaseRepository;
use app\common\repositories\user\UserRepository;
use think\exception\ValidateException;
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

    public function getAPIConfig($usage)
    {
        $aiToolsApiConfig = systemGroupData('ai_tools_api_config');

        if (!$aiToolsApiConfig) {
            new ValidateException('未配置AI工具API，请联系管理员');
        }

        $aiToolsApiConfig = array_filter($aiToolsApiConfig, function ($item) use ($usage) {
            return $item['usage'] == $usage;
        });

        if (empty($aiToolsApiConfig)) {
            throw new ValidateException('暂不支持' . $usage);
        }

        return $aiToolsApiConfig[array_key_last($aiToolsApiConfig)];
    }

    public function recreate($id, $user, $data)
    {
        $scriptInitiationRepository = app()->make(ScriptInitiationRepository::class);
        $existsInitiation = $scriptInitiationRepository->existsInitiation($id, $data['uid']);

        if (!$existsInitiation) {
            return null;
        }
        
        $toolsRepository = app()->make(ToolsRepository::class);

        $expires = $toolsRepository->validateVIPExpired($data['uid']);

        $rewriteType = 'recreate';
        $apiConfig = $this->getAPIConfig($rewriteType);

        $integral = $apiConfig['integral'];

        $remain = $toolsRepository->getRemain($user, $integral);

        $contentRes = $toolsRepository->rewriteContent($data, $rewriteType, $apiConfig);

        $returnData = [
            'content' => $contentRes['text'],
            'used' => $integral,
            'remain' => $remain,
            'expires' => $expires,
        ];

        $res = Db::transaction(function () use ($data, $existsInitiation, $contentRes, $returnData, $scriptInitiationRepository, $rewriteType, $user) {
            $initiation_id = (int) $existsInitiation['initiation_id'];
            $createTime = date('Y-m-d H:i:s');
            $create = $this->dao->create([
                'initiation_id' => $initiation_id,
                'uid' => $data['uid'],
                'original' => $data['original'],
                'prompt' => $data['prompt'],
                'rewrite' => $contentRes['text'],
                'method' => strtolower($rewriteType),
                'create_time' => $createTime,
            ]);

            $scriptInitiationRepository->update($existsInitiation['initiation_id'], [
                'original' => $data['original'],
                'prompt' => $data['prompt'],
                'last_update_time' => $create['create_time']
            ]);

            $version_id = (int) $create[$this->dao->getPk()];

            $userRepository = app()->make(UserRepository::class);
            $userRepository->changeIntegral($user['uid'], $version_id, 0, $returnData['used'], [
                'title' => '重新生成文案',
                'mark' => '重新生成文案减少了' . $returnData['used'] . '积分',
                'bill_type' => 'ai_dec',
            ]);

            $returnData['id'] = $version_id;
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

    public function polish($id, $user, $data)
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

        $rewriteType = 'polish';
        $apiConfig = $this->getAPIConfig($rewriteType);

        $integral = $apiConfig['integral'];

        $remain = $toolsRepository->getRemain($user, $integral);

        $contentRes = $toolsRepository->rewriteContent([
            'original' => $exists['rewrite'],
            'prompt' => $data['prompt'],
        ], $rewriteType, $apiConfig);

        $returnData = [
            'content' => $contentRes['text'],
            'used' => $integral,
            'remain' => $remain,
            'expires' => $expires,
        ];

        $res = Db::transaction(function () use ($data, $exists, $contentRes, $returnData, $scriptInitiationRepository, $rewriteType, $user) {
            $initiation_id = (int) $exists['initiation_id'];
            $create = $this->dao->create([
                'initiation_id' => $initiation_id,
                'uid' => $data['uid'],
                'original' => $exists['rewrite'],
                'prompt' => $data['prompt'],
                'rewrite' => $contentRes['text'],
                'method' => $rewriteType,
            ]);

            $scriptInitiationRepository->update($exists['initiation_id'], ['last_update_time' => $create['create_time']]);

            $version_id = (int) $create[$this->dao->getPk()];

            $userRepository = app()->make(UserRepository::class);
            $userRepository->changeIntegral($user['uid'], $version_id, 0, $returnData['used'], [
                'title' => '润色文案',
                'mark' => '润色文案减少了' . $returnData['used'] . '积分',
                'bill_type' => 'ai_dec',
            ]);

            $returnData['id'] = $version_id;
            $returnData['initiation_id'] = $initiation_id;
            $returnData['createTime'] = $create['create_time'];

            return $returnData;
        });

        return $res;
    }
}
