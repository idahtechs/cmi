<?php

namespace app\common\repositories\ai;

use app\common\repositories\BaseRepository;
use crmeb\services\HttpService;
use think\exception\ValidateException;
use think\facade\Log;

class ToolsRepository extends BaseRepository
{
    public $apiDomain;
    public $apiHeader;

    public function __construct()
    {
        $this->apiDomain = env('AI_API_DOMAIN');
        $this->apiHeader = ['x-api-key:' . env('AI_API_KEY')];
    }

    public function getExtractContentApi($platform, $type = 'preview_info')
    {
        switch ($platform) {
            case 'douyin':
            case 'xhs':
            case 'bilibili':
            case 'wechat_public_account_article':
                $apiPre = $platform;
                break;

            default:
                throw new ValidateException('暂不支持的此平台！');
        }

        $canPreview = $type == 'preview_info' && $platform != 'wechat_public_account_article';

        if ($type != 'to_text' && !$canPreview) {
            throw new ValidateException('不支持的类型：' . $type);
        }

        return $this->apiDomain . '/' . $apiPre . '_' . $type;
    }

    public function getRewriteApi($type = 'rewrite')
    {
        $apiPre = $type;
        // TODO: 仿写链接
        $apiUrl = $this->apiDomain. '/'. $apiPre;

        return $apiUrl;
    }

    public function getIntegralRequire($url, $platform)
    {
        $isArticle = $platform == 'wechat_public_account_article';

        if ($isArticle) {
            // 微信公众号文章扣减积分
            return 1;
        }

        $videoInfo = $this->validateVideoUrl($url, $platform);

        return $this->calculateIntegral($videoInfo['duration']);
    }

    /**
     * TODO: 验证用户会员到期时间
     * @param integer $uid
     * @throws \think\exception\ValidateException
     * @return mixed
     */
    public function validateVIPExpired($uid)
    {
        $random = '+' . random_int(0,1) . ' second';
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

        $res = HttpService::request($checkApi, 'post', ['share_url' => $url], $this->apiHeader);

        if (!$res) {
            throw new ValidateException('验证失败，请联系管理员！');
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
    public function calculateIntegral($duration)
    {
        $perUnit = 1000 * 60 * 2; // TODO: 每2分钟扣减1，不足2分钟算2分钟
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
        $res = HttpService::request($apiUrl, 'post', ['url' => $data['url']], $this->apiHeader);

        if (!$res) {
            throw new ValidateException('提取失败，请联系管理员！');
        }

        $result = json_decode($res, true);

        if ($result['code'] != 0) {
            Log::error("调用 $apiUrl 返回失败：" . $res);
            throw new ValidateException('请填写正确的链接后重试！');
        }

        return $result;
    }

    /**
     * 调用API仿写文案
     * @param array $data
     * @throws \think\exception\ValidateException
     * @return mixed
     */
    public function rewriteContent($data)
    {
        // TODO: 调用API仿写文案
        $apiUrl = $this->getRewriteApi('rewrite');
        $res = HttpService::request($apiUrl, 'post', [
            'original' => $data['original'],
            'prompt' => $data['prompt'],
        ], $this->apiHeader);

        if (!$res) {
            throw new ValidateException('仿写失败，请联系管理员！');
        }

        $result = json_decode($res, true);

        if ($result['code'] != 0) {
            Log::error("调用 $apiUrl 返回失败：" . $res);
            throw new ValidateException('未知错误，请稍后再试！');
        }

        return $result;
    }
}