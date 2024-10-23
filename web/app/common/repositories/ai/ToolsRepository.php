<?php

namespace app\common\repositories\ai;

use app\common\repositories\BaseRepository;
use crmeb\services\HttpService;
use think\exception\ValidateException;
use think\facade\Log;

class ToolsRepository extends BaseRepository
{
    protected $timeout;
    protected $minuteLimit;

    public function __construct()
    {
        $this->timeout = 60 * 5;
        $this->minuteLimit = 4;
    }

    public function getIntegralRequire($url, $apiConfig = [])
    {
        if (!$apiConfig['to_text']['unit_time']) {
            return $apiConfig['to_text']['integral'];
        }
        
        $videoInfo = $this->validateVideoUrl($url, $apiConfig['preview_info']);

        return $this->calculateIntegral($videoInfo['duration'], $apiConfig['to_text']);
    }

    /**
     * TODO: 验证用户会员到期时间
     * @param object $user
     * @throws \think\exception\ValidateException
     * @return mixed
     */
    public function validateVIPExpired($user)
    {
        $expires = $user->svip_endtime;
        if ($user->is_svip == 3) {
            $expires = date('Y-m-d H:i:s', strtotime("+100 year"));
        }

        // TODO: 暂不验证过期时间
        $expires = date('Y-m-d H:i:s', strtotime("+100 year"));
  
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
    public function validateVideoUrl($url, $apiConfig = [])
    {
        $checkApi = $apiConfig['api_url'];

        $res = HttpService::request($checkApi, 'post', json_encode([
            'share_url' => $url
        ]), [
            'x-api-key: ' . $apiConfig['api_key'],
            'Content-Type: application/json'
        ], $this->timeout);

        if (!$res) {
            throw new ValidateException('验证失败，请重试！');
        }

        $result = json_decode($res, true);

        if ($result['code'] != 0) {
            Log::error("调用 $checkApi 返回结果：" . $res);
            throw new ValidateException('请填写正确的链接后重试！');
        }
        
        if ($result['duration'] > $this->minuteLimit * 60 * 1000) {
            throw new ValidateException("不支持长视频，时长不能超过" . $this->minuteLimit . "分钟！");
        }

        return $result;
    }

    /**
     * 计算视频时长使用积分
     * @param integer $duration
     * @throws \think\exception\ValidateException
     * @return integer
     */
    public function calculateIntegral($duration, $apiConfig = [])
    {
        $perUnit = 1000 * $apiConfig['unit_time'];
        
        $integral = ceil($duration / $perUnit) * $apiConfig['integral'];

        return $integral;
    }

    /**
     * 计算用户剩余积分
     * @param object $user
     * @param integer $integral
     * @throws \think\exception\ValidateException
     * @return integer
     */
    public function getRemain($user, $integral)
    {
        $remain = $user->integral;

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
    public function extractContent($data, $apiConfig = [])
    {
        $apiUrl = $apiConfig['api_url'];

        $res = HttpService::request($apiUrl, 'post', json_encode([
            'share_url' => $data['url']
        ]), [
            'x-api-key: ' . $apiConfig['api_key'],
            'Content-Type: application/json'
        ], $this->timeout);

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
    public function rewriteContent($data, $type = 'INITIATION', $config = [])
    {
        $type = strtoupper($type);
        if (!in_array($type, ['INITIATION', 'RECREATE', 'POLISH'])) {
            throw new ValidateException('暂不支持的类型：' . $type);
        }

        $apiUrl = $config['api_url'];

        $res = HttpService::request($apiUrl, 'post', json_encode([
            'inputs' => [
                'content' => $data['original'],
                'prompt' => $data['prompt']
            ],
            'response_mode' => 'blocking',
            'user' => $config['api_user'],
        ]), [
            'Authorization: Bearer ' . $config['api_key'],
            'Content-Type: application/json'
        ], $this->timeout);

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
