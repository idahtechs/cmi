<?php

// +----------------------------------------------------------------------
// | CRMEB [ CRMEB赋能开发者，助力企业发展 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016~2022 https://www.crmeb.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed CRMEB并不是自由软件，未经许可不能去掉CRMEB相关版权
// +----------------------------------------------------------------------
// | Author: CRMEB Team <admin@crmeb.com>
// +----------------------------------------------------------------------


namespace app\controller\api\user;

use app\common\repositories\ai\prompt\PromptTemplateRepository;
use app\validate\api\ai\prompt\PromptTemplateValidate;
use crmeb\basic\BaseController;
use think\App;
use think\exception\ValidateException;

class PromptTemplate extends BaseController
{
    protected $repository;
    protected $user;
    protected $uid;
    public function __construct(App $app, PromptTemplateRepository $repository)
    {
        parent::__construct($app);
        $this->repository = $repository;
        $this->user = $this->request->userInfo();
        $this->uid = $this->user['uid'];
    }

    public function create(PromptTemplateValidate $validate)
    {
        $uid = $this->uid;
        $data = $this->request->params(['title', 'prompt', ['type', ''], ['sort', 0], ['status', 1]]);
        if (!$data['title']) $data['title'] = uniqid('模板_');
        $validate->check($data);
        $data['uid'] = $uid;

        $count = $this->repository->count(['uid' => $this->uid, 'is_del' => 0]);

        $limit = $this->repository->userCustomLimit($this->user->is_svip);

        if ($count >= $limit && $limit > 0) {
            return app('json')->fail('模板数量已达上限');
        }

        $res = $this->repository->create($data);

        return app('json')->success($res);
    }

    public function delete($id)
    {
        $this->repository->delete($id, $this->uid);
        return app('json')->success('删除成功');
    }

    public function update($id, PromptTemplateValidate $validate)
    {
        $data = $this->request->params(['title', 'prompt', ['type', ''], ['sort', 0], ['status', 1]]);
        if (!$data['title']) unset($data['title']);
        $validate->check($data);
        $res = $this->repository->update($id, $this->uid, $data);
        unset($res['admin_id']);
        return app('json')->success($res);
    }

    public function detail($id)
    {
        $res = $this->repository->detail($id, $this->uid);
        unset($res['admin_id']);
        return app('json')->success($res);
    }

    public function lst()
    {
        [$page, $limit] = $this->getPage();
        $where = ['uids' => [0, $this->uid], 'is_del' => 0, 'status' => 1];
        $hidden = ['admin_id'];
        $res = $this->repository->lst($where, $page, $limit, $hidden);
        return app('json')->success($res);
    }
}
