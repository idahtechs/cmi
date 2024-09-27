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


namespace app\common\repositories\wechat;


use app\common\dao\wechat\WechatUserDao;
use app\common\repositories\article\ArticleRepository;
use app\common\repositories\BaseRepository;
use app\common\repositories\user\UserRepository;
use crmeb\jobs\SendNewsJob;
use crmeb\services\MiniProgramService;
use crmeb\services\WechatUserGroupService;
use crmeb\services\WechatUserTagService;
use FormBuilder\Exception\FormBuilderException;
use FormBuilder\Factory\Elm;
use FormBuilder\Form;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;
use think\Exception;
use think\exception\ValidateException;
use think\facade\Cache;
use think\facade\Db;
use think\facade\Queue;
use think\facade\Route;

/**
 * Class WechatUserRepository
 * @package app\common\repositories\wechat
 * @author xaboy
 * @day 2020-04-28
 * @mixin WechatUserDao
 */
class WechatUserRepository extends BaseRepository
{
    /**
     * WechatUserRepository constructor.
     * @param WechatUserDao $dao
     */
    public function __construct(WechatUserDao $dao)
    {
        $this->dao = $dao;
    }

    /**
     *  获取小程序用户，是否需要绑定手机号
     * @param $code
     * @return array
     * @author Qinii
     * @day 2023/11/9
     */
    public function mpLoginType($code, $spread)
    {
        if (!$code )
            throw new ValidateException('授权失败,请获取code参数');
        $userInfoCong = Cache::get('eb_api_code_' . $code);
        if (!$userInfoCong) {
            try {
                $userInfoCong =  MiniProgramService::create()->getUserInfo($code);
                Cache::set('eb_api_code_' . $code, $userInfoCong, 86400);
            } catch (Exception $e) {
                throw new ValidateException('获取session_key失败，请检查您的配置！'.$e->getMessage());
            }
        }
        $bindPhone = systemConfig('is_phone_login') == '1';
        $key = '';
        $wechat_phone_switch = systemConfig('wechat_phone_switch');

        if ($bindPhone) {
            $routineInfo = $this->dao->routineIdByWechatUser($userInfoCong['openid']);
            if (!$routineInfo){
                $info = ['session_key' => $userInfoCong->session_key, 'unionId' => $userInfoCong->unionid];
                $routineInfo = $this->syncRoutineUser($userInfoCong['openid'], $info, false);
                $routineInfo = $routineInfo[0];
            }
            $user = app()->make(UserRepository::class)->getWhere(['wechat_user_id' => $routineInfo['wechat_user_id']]);
            if ($user && $user['phone'])
                $bindPhone = false;
            if ($bindPhone) {
                $uni = uniqid(true, false) . random_int(1, 100000000);
                $key = 'U' . md5(time() . $uni);
                Cache::set('u_try' . $key, ['id' => $routineInfo['wechat_user_id'], 'type' => $routineInfo['user_type'], 'spread' => $spread], 3600);
            }
        }
        return compact('bindPhone','key','wechat_phone_switch');
    }

    /**
     * @param string $openId
     * @param array $userInfo
     * @param bool $mode
     * @return mixed|void
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     * @author xaboy
     * @day 2020-04-28
     */

    public function syncUser(string $openId, array $userInfo, bool $mode = false, $createUser = true)
    {
        if (($mode && (!isset($userInfo['subscribe']) || !$userInfo['subscribe'])) || !isset($userInfo['openid']))
            return;
        $wechatUser = null;
        $userInfo['nickname'] = filter_emoji(($userInfo['nickname'] ?? '') ?: ('微信用户U' . substr(uniqid(true, true), -6)));
        if (isset($userInfo['unionid']))
            $wechatUser = $this->dao->unionIdByWechatUser($userInfo['unionid']);
        if (!$wechatUser)
            $wechatUser = $this->dao->openIdByWechatUser($openId);
        unset($userInfo['qr_scene'], $userInfo['qr_scene_str'], $userInfo['qr_scene_str'], $userInfo['subscribe_scene']);

        if (isset($userInfo['tagid_list'])) {
            $userInfo['tagid_list'] = implode(',', $userInfo['tagid_list']);
        }

        return Db::transaction(function () use ($createUser, $mode, $userInfo, $wechatUser) {
            if ($wechatUser) {
                if ($mode) {
                    unset($userInfo['nickname']);
                }
                $wechatUser->save($userInfo);
            } else {
                $wechatUser = $this->dao->create($userInfo);
            }
            if (!$createUser) return [$wechatUser];
            /** @var UserRepository $userRepository */
            $userRepository = app()->make(UserRepository::class);
            $user = $userRepository->syncWechatUser($wechatUser);
            return [$wechatUser, $user];
        });
    }

    /**
     * @param string $routineOpenid
     * @param array $routine
     * @return mixed
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     * @author xaboy
     * @day 2020-05-11
     */
    public function  syncRoutineUser(string $routineOpenid, array $routine, $createUser = true)
    {
        $routineInfo = [];
        $nickname = $routine['nickName'] ?? '小程序用户';
        $routineInfo['nickname'] = $nickname; //姓名
        $routineInfo['sex'] = $routine['gender'] ?? 0;//性别
        $routineInfo['language'] = $routine['language'] ?? '';//语言
        $routineInfo['city'] = $routine['city'] ?? '';//城市
        $routineInfo['province'] = $routine['province'] ?? '';//省份
        $routineInfo['country'] = $routine['country'] ?? '';//国家
        $routineInfo['headimgurl'] = '';//头像
        $routineInfo['routine_openid'] = $routineOpenid;//openid
        $routineInfo['session_key'] = $routine['session_key'] ?? '';//会话密匙
        $routineInfo['unionid'] = $routine['unionId'];//用户在开放平台的唯一标识符
        $routineInfo['user_type'] = 'routine';//用户类型
        $wechatUser = null;
        if ($routineInfo['unionid'])
            $wechatUser = $this->dao->unionIdByWechatUser($routineInfo['unionid']);
        if (!$wechatUser)
            $wechatUser = $this->dao->routineIdByWechatUser($routineOpenid);
        return Db::transaction(function () use ($createUser, $routineInfo, $wechatUser) {
            if ($wechatUser) {
                $routineInfo['nickname'] = $wechatUser['nickname'];
                $routineInfo['headimgurl'] = $wechatUser['headimgurl'];
                $wechatUser->save($routineInfo);
            } else {
                $wechatUser = $this->dao->create($routineInfo);
            }
            if (!$createUser) return [$wechatUser];
            /** @var UserRepository $userRepository */
            $userRepository = app()->make(UserRepository::class);
            $user = $userRepository->syncWechatUser($wechatUser, 'routine');
            return [$wechatUser, $user];
        });
    }

    /**
     * @param string $routineOpenid
     * @param array $routine
     * @return mixed
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     * @author xaboy
     * @day 2020-05-11
     */
    public function syncAppUser(string $unionId, array $userInfo, $type = 'wechat', $createUser = true)
    {
        $wechatInfo = [];
        $wechatInfo['nickname'] = filter_emoji($userInfo['nickName'] ?? ($userInfo['nickname'] ?? ''));//姓名
        $wechatInfo['sex'] = $userInfo['gender'] ?? 0;//性别
        $wechatInfo['city'] = $userInfo['city'] ?? '';//城市
        $wechatInfo['province'] = $userInfo['province'] ?? '';//省份
        $wechatInfo['country'] = $userInfo['country'] ?? '';//国家
        $wechatInfo['headimgurl'] = $userInfo['avatarUrl'] ?? ($userInfo['headimgurl'] ?? '');//头像
        $wechatInfo['unionid'] = $unionId;//用户在开放平台的唯一标识符
        $wechatInfo['user_type'] = 'app';//用户类型
        $wechatUser = $this->dao->unionIdByWechatUser($unionId);

        return Db::transaction(function () use ($createUser, $type, $wechatInfo, $wechatUser) {
            if ($wechatUser) {
                unset($wechatInfo['nickname']);
                $wechatUser->save($wechatInfo);
            } else {
                $wechatUser = $this->dao->create($wechatInfo);
            }
            if (!$createUser) {
                return [$wechatUser];
            }
            /** @var UserRepository $userRepository */
            $userRepository = app()->make(UserRepository::class);
            $user = $userRepository->syncWechatUser($wechatUser, $type);
            return [$wechatUser, $user];
        });
    }

    /**
     * @param array $where
     * @param $page
     * @param $limit
     * @return array
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     * @author xaboy
     * @day 2020-04-29
     */
    public function getList(array $where, $page, $limit)
    {
        $query = $this->dao->search($where);
        $count = $query->count($this->dao->getPk());
        $list = $query->setOption('field', [])->field('uid,openid,nickname,headimgurl,sex,country,province,city,subscribe')
            ->page($page, $limit)->select()->each(function ($item) {
                $item['subscribe_time'] = $item['subscribe_time'] ? date('Y-m-d H:i', $item['subscribe_time']) : '';
                return $item;
            });
        return compact('count', 'list');
    }


    /**
     * @param $id
     * @return Form
     * @throws DataNotFoundException
     * @throws DbException
     * @throws FormBuilderException
     * @throws ModelNotFoundException
     * @author xaboy
     * @day 2020-04-29
     */
    public function updateUserTagForm($id)
    {
        $wechatUserTagService = new WechatUserTagService();
        $lst = $wechatUserTagService->lst();
        $user = $this->dao->get($id);
        return Elm::createForm(Route::buildUrl('wechat/user/tag', ['id' => $id]), [
            Elm::select('tag_id', '用户标签', explode(',', $user->tagid_list))->options(function () use ($lst) {
                $options = [];
                foreach ($lst as $item) {
                    $options[] = ['value' => $item['id'], 'label' => $item['name']];
                }
                return $options;
            })->multiple(true)
        ])->setTitle('编辑用户标签');
    }

    /**
     * @param $id
     * @param array $tags
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     * @author xaboy
     * @day 2020-04-29
     */
    public function updateTag($id, array $tags)
    {
        $user = $this->dao->get($id);
        $oTags = explode(',', $user->tagid_list);
        $user->save(['tagid_list' => implode(',', $tags)]);
        $wechatUserTagService = (new WechatUserTagService())->userTag();
        foreach ($oTags as $tag) {
            $wechatUserTagService->batchUntagUsers([$user->openid], $tag);
        }
        foreach ($tags as $tag) {
            $wechatUserTagService->batchTagUsers([$user->openid], $tag);
        }
    }


    /**
     * @param $id
     * @return Form
     * @throws DataNotFoundException
     * @throws DbException
     * @throws FormBuilderException
     * @throws ModelNotFoundException
     * @author xaboy
     * @day 2020-04-29
     */
    public function updateUserGroupForm($id)
    {
        $wechatUserGroupService = new WechatUserGroupService();
        $lst = $wechatUserGroupService->lst();
        $user = $this->dao->get($id);
        return Elm::createForm(Route::buildUrl('wechat/user/group', ['id' => $id]), [
            Elm::select('group_id', '用户标签', (string)$user->groupid)->options(function () use ($lst) {
                $options = [];
                foreach ($lst as $item) {
                    $options[] = ['value' => $item['id'], 'label' => $item['name']];
                }
                return $options;
            })
        ])->setTitle('编辑用户分组');
    }

    /**
     * @param $id
     * @param $groupid
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     * @author xaboy
     * @day 2020-04-29
     */
    public function updateGroup($id, $groupid)
    {
        $user = $this->dao->get($id);
        $user->save(['groupid' => $groupid]);
        $wechatUserGroupService = (new WechatUserGroupService())->userGroup();
        $wechatUserGroupService->moveUser($user->openid, $groupid);
    }


    /**
     * @param $id
     * @param array $ids
     * @author xaboy
     * @day 2020-05-11
     */
    public function sendNews($id, array $ids)
    {
        if (!count($ids)) return;
        /** @var ArticleRepository $make */
        $make = app()->make(ArticleRepository::class);
        $articles = $make->wechatNewIdByData($id);
        $news = [];
        foreach ($articles as $article) {
            $news[] = [
                'title' => $article['title'],
                'image' => $article['image_input'],
                'date' => $article['create_time'],
                'description' => $article['synopsis'],
                'id' => $article['article_id']
            ];
        }
        $make = app()->make(UserRepository::class);
        foreach ($ids as $_id) {
            $user = $make->get($_id);
            if ($this->dao->isSubscribeWechatUser($user->wechat_user_id)) {
                Queue::push(SendNewsJob::class, [$user->wechat_user_id, $news]);
            }
        }
    }

}
