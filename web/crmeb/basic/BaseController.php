<?php
declare (strict_types=1);

namespace crmeb\basic;

use app\Request;
use crmeb\services\HttpService;
use RuntimeException;
use think\App;
use think\exception\ValidateException;
use think\Validate;

/**
 * 广州爱达资讯科技有限公司
 * 控制器基础类
 */
abstract class BaseController
{
    /**
     * Request实例
     * @var Request
     */
    protected $request;

    /**
     * 应用实例
     * @20231129
     * @var \think\App
     */
    protected $app;

    /**
     * 是否批量验证
     * @var bool
     */
    protected $batchValidate = false;

    /**
     * 通过header token获取 admin 信息
     * @var bool
     */
    protected $adminInfo = [];

    /**
     * 控制器中间件
     * @var array
     */
    protected $middleware = [];

    /**
     * 构造方法
     * @access public
     * @param App $app 应用对象
     */
    public function __construct(App $app)
    {
        $this->app = $app;
        $this->request = $this->app->request;

        // 控制器初始化
        $this->initialize();
    }

    // 初始化
    protected function initialize()
    {
    }

    /**
     * 验证数据
     * @access protected
     * @param array $data 数据
     * @param string|array $validate 验证器名或者验证规则数组
     * @param array $message 提示信息
     * @param bool $batch 是否批量验证
     * @return array|string|true
     * @throws ValidateException
     */
    protected function validate(array $data, $validate, array $message = [], bool $batch = false)
    {
        if (is_array($validate)) {
            $v = new Validate();
            $v->rule($validate);
        } else {
            if (strpos($validate, '.')) {
                // 支持场景
                [$validate, $scene] = explode('.', $validate);
            }
            $class = false !== strpos($validate, '\\') ? $validate : $this->app->parseClass('validate', $validate);
            $v = new $class();
            if (!empty($scene)) {
                $v->scene($scene);
            }
        }

        $v->message($message);

        // 是否批量验证
        if ($batch || $this->batchValidate) {
            $v->batch(true);
        }

        return $v->failException(true)->check($data);
    }

    /**
     * 设置分页数据
     * @access protected
     * @return array
     * @throws ValidateException
     */
    protected function getPage()
    {
        $res = $this->request->params([['page', 1], ['limit', 10]], true);
        $res[0] = max(intval($res[0]), 1);
        return $res;
    }
}
