<?php

namespace app\common\repositories\ai\tools;


use app\common\dao\ai\tools\ExtractCopyDao as dao;
use app\common\repositories\BaseRepository;
use think\exception\ValidateException;

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

    public function byUrl($data)
    {
        // TODO: 查询用户会员到期时间
        $random = '+' . random_int(0,1) . ' second';
        $expires = date('Y-m-d H:i:s', strtotime($random));

        $now = date('Y-m-d H:i:s');

        if ($expires <= $now) {
            throw new ValidateException('您的会员已过期，请续费后再试！');
        }

        // TODO: 验证链接视频有效性？API是否有做验证？
        $is_invalid = random_int(0, 1) == 0;

        if ($is_invalid) {
            throw new ValidateException('请填写正确的视频链接！');
        }
        // TODO: 计算视频次数？API是否有相应计算？
        $times = random_int(1, 3);

        // TODO: 查询用户剩余次数
        $remain = random_int(0, 10);

        if ($remain < $times) {
            throw new ValidateException('您的次数已不足，本次提取所需次数：' . $times . '，剩余次数：' . $remain);
        }

        $data['times'] = $times;

        try {
            // TODO: 调用API提取文案
            $data['content'] = '测试文案';
        } catch (\Throwable $th) {
            throw new ValidateException('提取失败，请联系管理员！');
        }

        $data = $this->dao->create($data);

        $res = [
            'content' => $data['content'],
            'used' => $data['times'],
            'remain' => $remain - $data['times'],
            'expires' => $expires,
        ];

        return $res;
    }
}
