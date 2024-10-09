<?php

namespace app\controller\api\ai;

use think\App;
use crmeb\basic\BaseController;
use app\validate\api\ai\tools\ExtractCopyValidate;
use app\common\repositories\ai\tools\ExtractCopyRepository;

class Tools extends BaseController
{

    protected $uid;

    public function __construct(App $app)
    {
        parent::__construct($app);
        $this->uid = $this->request->uid();
    }

    /**
     * 通过视频url提取文案
     * @param ExtractCopyValidate $validate
     * @return mixed
     */
    public function extractCopyByUrl(ExtractCopyValidate $validate)
    {
        $data = $this->request->params(['url', 'platform']);

        if (empty($data['palatform'])) {
            $data['platform'] = 'douyin';
        }

        $validate->check($data);

        $extractCopyRepository = app()->make(ExtractCopyRepository::class);

        $data['uid'] = $this->uid;

        $res = $extractCopyRepository->byUrl($data);

        return app('json')->success($res);
    }
}
