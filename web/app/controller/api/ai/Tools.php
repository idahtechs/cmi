<?php

namespace app\controller\api\ai;

use think\App;
use crmeb\basic\BaseController;
use app\validate\api\ai\tools\ExtractCopyValidate;
use app\validate\api\ai\tools\ScriptInitiationValidate;
use app\validate\api\ai\tools\ScriptPolishValidate;
use app\validate\api\ai\tools\ScriptRewriteValidate;
use app\common\repositories\ai\tools\ExtractCopyRepository;
use app\common\repositories\ai\tools\ScriptInitiationRepository;
use app\common\repositories\ai\tools\ScriptRewriteRepository;

class Tools extends BaseController
{

    protected $uid;
    protected $user;

    

    public function __construct(App $app)
    {
        parent::__construct($app);
        $this->user = $this->request->userInfo();
        $this->uid = $this->user['uid'];
    }

    /**
     * 通过视频url提取文案
     * @param ExtractCopyValidate $validate
     * @return mixed
     */
    public function extractCopyByUrl(ExtractCopyValidate $validate)
    {
        $data = $this->request->params(['url', 'platform']);

        if (empty($data['platform'])) {
            $data['platform'] = 'douyin';
        }

        $validate->check($data);

        $extractCopyRepository = app()->make(ExtractCopyRepository::class);

        $data['uid'] = $this->uid;

        $res = $extractCopyRepository->byUrl($this->user, $data);

        return app('json')->success($res);
    }

    /**
     * 仿写文案
     * @param ScriptInitiationValidate $validate
     * @return mixed
     */

    public function scriptInitiation(ScriptInitiationValidate $validate)
    {
        $data = $this->request->params(['original', 'prompt', 'extract_copy_id', 'type']);

        if (empty($data['extract_copy_id'])) {
            $data['extract_copy_id'] = 0;
        }

        $validate->check($data);

        $scriptInitiationRepository = app()->make(ScriptInitiationRepository::class);

        $data['uid'] = $this->uid;

        $res = $scriptInitiationRepository->initiation($this->user, $data);

        return app('json')->success($res);
    }

    /**
     * 重新生成文案
     * @param ScriptRewriteValidate $validate
     * @return mixed
     */
    public function scriptRecreate($id, ScriptRewriteValidate $validate)
    {
        $data = $this->request->params(['original', 'prompt']);

        $validate->check($data);

        $scriptRewriteRepository = app()->make(ScriptRewriteRepository::class);

        $data['uid'] = $this->uid;

        $rewrite = $scriptRewriteRepository->recreate($id, $this->user, $data);

        if (!$rewrite) {
            return app('json')->fail('无效的操作');
        }

        return app('json')->success($rewrite);
    }

    /**
     * 删除保存的文案
     * @param $id
     * @param ExtractCopyValidate $validate
     * @return mixed
     */
    public function scriptDelete($id)
    {
        $scriptInitiationRepository = app()->make(ScriptInitiationRepository::class);

        $res = $scriptInitiationRepository->destroy($id, $this->uid);

        if (!$res) {
            return app('json')->fail('无效的删除操作');
        }

        return app('json')->success('删除成功');
    }

    public function scriptLst()
    {
        $scriptInitiationRepository = app()->make(ScriptInitiationRepository::class);
        [$page, $limit] = $this->getPage();
        $where = ['uid' => $this->uid, 'is_del' => 0];
        $res = $scriptInitiationRepository->lst($where, $page, $limit);

        return app('json')->success($res);
    }

    public function scriptDetail($id)
    {
        $scriptInitiationRepository = app()->make(ScriptInitiationRepository::class);

        $res = $scriptInitiationRepository->detail($id, $this->uid);

        if (!$res) {
            return app('json')->fail('脚本不存在');
        }

        return app('json')->success($res);
    }

    public function scriptPolish($id, ScriptPolishValidate $validate)
    {
        $data = $this->request->params(['prompt']);
        
        $validate->check($data);
        
        $scriptRewriteRepository = app()->make(ScriptRewriteRepository::class);

        $data['uid'] = $this->uid;

        $polish = $scriptRewriteRepository->polish($id, $this->user, $data);

        if (!$polish) {
            return app('json')->fail('无效的操作');
        }

        return app('json')->success($polish);
    }

    public function scriptVersionDelete($id)
    {
        $scriptRewriteRepository = app()->make(ScriptRewriteRepository::class);

        $res = $scriptRewriteRepository->destroy($id, $this->uid);

        if (!$res) {
            return app('json')->fail('无效的删除操作');
        }

        return app('json')->success('删除成功');
    }
}
