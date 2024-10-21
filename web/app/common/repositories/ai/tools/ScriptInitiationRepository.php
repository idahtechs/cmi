<?php

namespace app\common\repositories\ai\tools;


use app\common\dao\ai\tools\ScriptInitiationDao as dao;
use app\common\repositories\ai\ToolsRepository;
use app\common\repositories\BaseRepository;
use app\common\repositories\user\UserRepository;
use think\exception\ValidateException;
use think\facade\Db;

class ScriptInitiationRepository extends BaseRepository
{
    /**
     * @var ScriptInitiationDao
     */
    public $dao;
    public $rewriteType;

    public function __construct(dao $dao)
    {
        $this->dao = $dao;
        $this->rewriteType = 'initiation';
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

    public function initiation($user, $data)
    {
        $toolsRepository = app()->make(ToolsRepository::class);
        $expires = $toolsRepository->validateVIPExpired($user);
        $apiConfig = $this->getAPIConfig($this->rewriteType);
        $integral = $apiConfig['integral'];
        $remain = $toolsRepository->getRemain($user, $integral);

        $contentRes = $toolsRepository->rewriteContent($data, $this->rewriteType, $apiConfig);
        $returnData = [
            'content' => $contentRes['text'],
            'used' => $integral,
            'remain' => $remain,
            'expires' => $expires,
        ];

        return Db::transaction(function() use ($data, $contentRes, $returnData, $user) {
            $createTime = date('Y-m-d H:i:s');
            $data['create_time'] = $createTime;
            $data['last_update_time'] = $createTime;
            $res = $this->dao->create($data);
            $initiation_id = (int) $res[$this->dao->getPk()];

            $scriptRewriteRepository = app()->make(ScriptRewriteRepository::class);
            $version = $scriptRewriteRepository->create([
                'initiation_id' => $initiation_id,
                'original' => $data['original'],
                'prompt' => $data['prompt'],
                'rewrite' => $contentRes['text'],
                'create_time' => $createTime,
            ]);
            $version_id = (int) $version[$scriptRewriteRepository->dao->getPk()];

            $userRepository = app()->make(UserRepository::class);
            $userRepository->changeIntegral($user['uid'], $version_id, 0, $returnData['used'], [
                'title' => '仿写文案',
                'mark' => '仿写文案减少了' . $returnData['used'] . '积分',
                'bill_type' => 'ai_dec',
            ]);

            $returnData['id'] = $version_id;
            $returnData['initiation_id'] = $initiation_id;
            $returnData['createTime'] = $createTime;

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
