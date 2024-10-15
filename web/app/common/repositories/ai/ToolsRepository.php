<?php

namespace app\common\repositories\ai;

use app\common\repositories\BaseRepository;
use crmeb\services\HttpService;
use think\exception\ValidateException;
use think\facade\Log;

class ToolsRepository extends BaseRepository
{
    public $apiCrawler;
    public $apiDefault;
    public $apiHeader;

    public function __construct()
    {
        $this->apiCrawler = env('AI_API_CRAWLER');
        $this->apiDefault = env('AI_API_DEFAULT');
        $this->apiHeader = ['x-api-key:' . env('AI_API_KEY'), 'Content-Type: application/json'];
    }

    public function getExtractContentApi($platform, $type = 'preview_info')
    {
        if (!in_array($platform, ['douyin', 'xhs', 'bilibili', 'wechat_public_account_article'])) {
            throw new ValidateException('暂不支持的此平台：' . $platform);
        }

        $canPreview = $type == 'preview_info' && $platform != 'wechat_public_account_article';

        if ($type != 'to_text' && !$canPreview) {
            throw new ValidateException('不支持的类型：' . $type);
        }

        return $this->apiCrawler . '/' . $platform . '_' . $type;
    }

    public function getIntegralRequire($url, $platform)
    {
        $isArticle = $platform == 'wechat_public_account_article';

        if ($isArticle) {
            // 微信公众号文章扣减积分
            return 1;
        }

        $videoInfo = $this->validateVideoUrl($url, $platform);

        return $this->calculateIntegral($videoInfo['duration'], $platform);
    }

    /**
     * TODO: 验证用户会员到期时间
     * @param integer $uid
     * @throws \think\exception\ValidateException
     * @return mixed
     */
    public function validateVIPExpired($uid)
    {
        // TODO:永不过期
        $random = '+' . random_int(1,1) . ' second';
        $expires = date('Y-m-d H:i:s', strtotime($random));

        $now = date('Y-m-d H:i:s');

        if ($expires <= $now) {
            throw new ValidateException('您的会员已过期，请续费后再试！');
        }

        return $expires;
    }

    /**
     * 验证视频链接有效性
     * @param string $url
     * @param string $platform
     * @throws \think\exception\ValidateException
     * @return mixed
     */
    public function validateVideoUrl($url, $platform)
    {
        $checkApi = $this->getExtractContentApi($platform, 'preview_info');

        $res = HttpService::request($checkApi, 'post', json_encode(['share_url' => $url]), $this->apiHeader);

        if (!$res) {
            throw new ValidateException('验证失败，请重试！');
        }

        $result = json_decode($res, true);

        if ($result['code'] != 0) {
            Log::error("调用 $checkApi 返回结果：" . $res);
            throw new ValidateException('请填写正确的链接后重试！');
        }
        
        if ($result['duration'] > 10 * 60 * 1000) {
            throw new ValidateException('视频时长不允许超过10分钟！');
        }

        return $result;
    }

    /**
     * 计算视频时长使用积分
     * @param integer $duration
     * @throws \think\exception\ValidateException
     * @return integer
     */
    public function calculateIntegral($duration, $platform)
    {
        $perUnit = 1000 * 60 * 2; // TODO: 每2分钟扣减1，不足2分钟算2分钟

        if ($platform == 'bilibili') {
            $perUnit = $perUnit * 2;
        }

        $integral = ceil($duration / $perUnit);

        return $integral;
    }

    /**
     * TODO: 计算用户剩余积分
     * @param integer $uid
     * @param integer $integral
     * @throws \think\exception\ValidateException
     * @return integer
     */
    public function getRemain($uid, $integral)
    {
        $remain = random_int(0, 5);
        // TODO: 无限积分
        $remain = $integral * 2;

        if ($remain < $integral) {
            throw new ValidateException('积分不足，本次所需积分：' . $integral . '，剩余积分：' . $remain);
        }

        return $remain - $integral;
    }

    /**
     * 调用API提取文案
     * @param array $data
     * @throws \think\exception\ValidateException
     * @return mixed
     */
    public function extractContent($data)
    {
        $apiUrl = $this->getExtractContentApi($data['platform'], 'to_text');
        $res = HttpService::request($apiUrl, 'post', json_encode(['share_url' => $data['url']]), $this->apiHeader);

        if (!$res) {
            throw new ValidateException('提取失败，请重试！');
        }

        $result = json_decode($res, true);

        if ($result['code'] != 0) {
            Log::error("调用 $apiUrl 返回失败：" . $res);
            throw new ValidateException('请填写正确的链接后重试！');
        }

        return $result;
    }

    /**
     * 调用API重写文案(仿写、重新生成、润色)
     * @param array $data
     * @throws \think\exception\ValidateException
     * @return mixed
     */
    public function rewriteContent($data, $type = 'INITIATION')
    {
        $type = strtoupper($type);
        if (!in_array($type, ['INITIATION', 'REWRITE', 'POLISH'])) {
            throw new ValidateException('暂不支持的类型：' . $type);
        }

        $apiUrl = $this->apiDefault;
        $res = HttpService::request($apiUrl, 'post', json_encode([
            'inputs' => [
                'content' => $data['original'],
                'prompt' => $data['prompt']
            ],
            'response_mode' => 'blocking',
            'user' => env('AI_API_USER')
        ]), [
            'Authorization: Bearer ' . env('AI_API_KEY_' . $type),
            'Content-Type: application/json'
        ]);

        if (!$res) {
            throw new ValidateException('操作失败，请重试！');
        }

        $result = json_decode($res, true);

        if (!isset($result['data']) || $result['data']['error']) {
            Log::error("调用 $apiUrl 返回失败：" . $res);
            throw new ValidateException('未知错误，请稍后再试！');
        }

        return $result['data']['outputs'];
    }
}
