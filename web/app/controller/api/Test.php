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
namespace app\controller\api;

use app\common\repositories\store\CityAreaRepository;
use app\common\repositories\store\order\StoreOrderStatusRepository;
use crmeb\basic\BaseController;
use think\Exception;
use think\exception\FileException;
use think\exception\ValidateException;
use think\facade\Cache;

class Test extends BaseController
{
    public function test()
    {
        $func = $this->request->param('func','');
        if (!$func) return app('json')->fail('缺少方法名');
        $data = $this->{$func}();
        return app('json')->success('ok',$data);
    }

    public function addres()
    {
        $fiel = root_path().'addres.txt';
        app()->make(CityAreaRepository::class)->updateCityForTxt($fiel);
        halt('sss');
    }

    public function time()
    {
        $timer = ((int)systemConfig('auto_take_order_timer')) ?: 15;
        $end = date('Y-m-d H:i:s', strtotime("- 10 day"));
        $start = date('Y-m-d H:i:s', strtotime("- 15 day",strtotime($end)));
        $ids = app()->make(StoreOrderStatusRepository::class)->getTimeoutDeliveryOrder($start,$end);
        return [$ids];
    }


    public function queue()
    {
        $file = app()->getRootPath().'queue_status.txt';
        try{
            $tiem = file_get_contents($file);
            if ($tiem) {
                $diff = (time() - $tiem);
                if ($diff > 1800){
                    throw new ValidateException('队列运行异常');
                }
            }
        }catch (Exception $exception) {
            throw new ValidateException('队列运行异常');
        }
        return ['time' => date('Y-m-d H:i:s',$tiem)];
    }

    public function cache()
    {
        $data = Cache::get('serve_account');
        halt($data);
    }

    public function config()
    {
        $data = systemConfig('serve_account',false);
        halt($data);
    }
}
