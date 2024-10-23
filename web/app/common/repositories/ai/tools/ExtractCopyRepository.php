<?php

namespace app\common\repositories\ai\tools;

use app\common\dao\ai\tools\ExtractCopyDao as dao;
use app\common\repositories\ai\ToolsRepository;
use app\common\repositories\BaseRepository;
use app\common\repositories\user\UserRepository;
use think\exception\ValidateException;
use think\facade\Db;

class ExtractCopyRepository extends BaseRepository
{
    /**
     * @var ExtractCopyDao
     */
    public $dao;

    public function __construct(dao $dao)
    {
        $this->dao = $dao;
    }

    public function getAPIConfig($usage, $platform = '', $checkError = true)
    {
        $aiToolsApiConfig = systemGroupData('ai_tools_api_config');

        if (!$aiToolsApiConfig) {
            new ValidateException('未配置AI工具API，请联系管理员');
        }

        $aiToolsApiConfig = array_filter($aiToolsApiConfig, function ($item) use ($usage, $platform) {
            return $item['usage'] == $usage && $item['platform'] == $platform;
        });

        if (empty($aiToolsApiConfig)) {
            if ($checkError) {
                throw new ValidateException($platform . '暂不支持' . $usage);
            }

            return [];
        }

        return $aiToolsApiConfig[array_key_last($aiToolsApiConfig)];
    }

    public function byUrl($user, $data)
    {
        $toolsRepository = app()->make(ToolsRepository::class);
        $expires = $toolsRepository->validateVIPExpired($user);

        $apiConfig = [
            'preview_info' => $this->getAPIConfig('preview_info', $data['platform'], $data['platform'] != 'wechat_public_account_article'),
            'to_text' => $this->getAPIConfig('to_text', $data['platform']),
        ];

        $integral = $toolsRepository->getIntegralRequire($data['url'], $apiConfig);
        $remain = $toolsRepository->getRemain($user, $integral);
        
        $data['integral'] = $integral;
        
        $contentRes = $toolsRepository->extractContent($data, $apiConfig['to_text']);
        $data['content'] = $contentRes['text'];
        $data['create_time'] = date('Y-m-d H:i:s');

        return Db::transaction(function() use ($data, $user, $integral, $remain, $expires) {
            $data = $this->dao->create($data);
            $id = (int) $data[$this->dao->getPk()];

            $userRepository = app()->make(UserRepository::class);
            if ($integral > 0) {
                $userRepository->changeIntegral($user['uid'], $id, 0, $integral, [
                    'title' => '创作',
                    'mark' => '用户提取文案时所消耗的积分',
                    'bill_type' => 'ai_dec',
                ]);
            }

            return [
                'id' => $id,
                'content' => $data['content'],
                'used' => $data['integral'],
                'remain' => $remain,
                'expires' => $expires,
            ];
        });
    }
}
