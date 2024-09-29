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


namespace app\controller;

use crmeb\basic\BaseController;
use Redis;
use think\App;
use think\Exception;
use think\exception\ValidateException;
use think\facade\Config;
use think\facade\View;
use think\Request;
use think\Response;
use Throwable;
use ZipArchive;
use ZipStream\ZipStream;

class Install //extends BaseController
{

    /**
     * Request实例
     * @var Request
     */
    protected $request;

    /**
     * 应用实例
     * @var App
     */
    protected $app;
    /**
     * sql文件
     * @var App
     */
    public $sqlFile = 'crmeb_merchant.sql';
    /**
     * 配置文件
     * @var App
     */
    public $configFile = '.env';
    public $env;
    public $installHost;
    public $_url;
    const DEMO_SQL_TABLE = [
        'eb_system_admin',
        'eb_system_menu',
        'eb_system_role',
        'eb_system_group',
        'eb_system_group_data',
        'eb_express',
        'eb_system_config',
        'eb_system_config_classify',
        'eb_system_config_value',
        'eb_template_message',
        'system_notice_config',
        'eb_city_area',
        'eb_diy',
        'eb_page_category',
        'eb_page_link',
        'eb_system_notice_config'
    ];

    public function __construct(App $app)
    {
        if (file_exists(__DIR__ . '/../../install/install.lock')) {
            throw new ValidateException('你已经安装过该系统，如果想重新安装，请先删除install目录下的 install.lock 文件，然后再安装。');
        }
        if (!file_exists(__DIR__ . '/../../install/' . $this->sqlFile)) {
            throw new ValidateException('缺少数据库文件："install/'.$this->sqlFile.'"');
        }
        if (!file_exists(__DIR__ . '/../../install/' . $this->configFile)) {
            throw new ValidateException('缺少配置文件："install/'.$this->configFile.'"');
        }
        $this->app = $app;
        $this->request = $this->app->request;
        $this->env = [];
        $this->installHost = $this->request->domain();
        if (substr($this->installHost, 0, 5) == 'https'){
            $this->_url = str_replace('//' ,'\\\/\\\/', $this->installHost);
        } else {
            $this->_url = str_replace('http://' ,'http:\\\/\\\/', $this->installHost);
        }
    }

    /**
     * TODO 1 开始安装
     * @return string
     * @author Qinii
     * @day 2020-07-16
     */
    public function begin()
    {
        return View::fetch('/install/step1');
    }

    /**
     * TODO 2 环境检测
     * @return string
     * @author Qinii
     * @day 2020-07-16
     */
    public function environment()
    {
        $phpv = @ phpversion();
        $os = PHP_OS;
        $tmp = function_exists('gd_info') ? gd_info() : array();
        $max_execution_time = ini_get('max_execution_time');
        $allow_reference = (ini_get('allow_call_time_pass_reference') ? '<font color=green>[√]On</font>' : '<font color=red>[×]Off</font>');
        $allow_url_fopen = (ini_get('allow_url_fopen') ? '<font color=green>[√]On</font>' : '<font color=red>[×]Off</font>');
        $safe_mode = (ini_get('safe_mode') ? '<font color=red>[×]On</font>' : '<font color=green>[√]Off</font>');



        $err = 0;
        if (empty($tmp['GD Version'])) {
            $gd = '<font color=red>[×]Off</font>';
            $err++;
        } else {
            $gd = '<font color=green>[√]On</font> ' . $tmp['GD Version'];
        }
        if (function_exists('mysqli_connect')) {
            $mysql = '<span class="correct_span">&radic;</span> 已安装';
        } else {
            $mysql = '<span class="correct_span error_span">&radic;</span> 请安装mysqli扩展';
            $err++;
        }
        if (ini_get('file_uploads')) {
            $uploadSize = '<span class="correct_span">&radic;</span> ' . ini_get('upload_max_filesize');
        } else {
            $uploadSize = '<span class="correct_span error_span">&radic;</span>禁止上传';
        }
        if (function_exists('session_start')) {
            $session = '<span class="correct_span">&radic;</span> 支持';
        } else {
            $session = '<span class="correct_span error_span">&radic;</span> 不支持';
            $err++;
        }
        if (extension_loaded('zip')) {
            $zip = '<font color=green>[√]支持</font> ';
        } else {
            $zip = '<font color=red>[×]不支持</font>';
            $err++;
        }
        if (extension_loaded(('redis'))) {
            $redis = '<font color=green>[√]支持</font> ';
        } else {
            $redis = '<font color=red>[×]不支持</font>';
            $err++;
        }
        if (extension_loaded('swoole')) {
            $swoole = '<font color=green>[√]支持</font> ';
        } else {
            $swoole = '<font color=red>[×]不支持</font>';
            $err++;
        }
        if (function_exists('curl_init')) {
            $curl = '<font color=green>[√]支持</font> ';
        } else {
            $curl = '<font color=red>[×]不支持</font>';
            $err++;
        }

        if (function_exists('bcadd')) {
            $bcmath = '<font color=green>[√]支持</font> ';
        } else {
            $bcmath = '<font color=red>[×]不支持</font>';
            $err++;
        }
        if (function_exists('openssl_encrypt')) {
            $openssl = '<font color=green>[√]支持</font> ';
        } else {
            $openssl = '<font color=red>[×]不支持</font>';
            $err++;
        }
        if (function_exists('finfo_open')) {
            $finfo_open = '<font color=green>[√]支持</font> ';
        } else {
            $finfo_open = '<font color=red>[×]不支持</font>';
            $err++;
        }
        $folder = array(
            'public/install',
            'public/uploads',
            'runtime',
            '.env',
        );
        //必须开启函数
        if (function_exists('file_put_contents')) {
            $file_put_contents = '<font color=green>[√]开启</font> ';
        } else {
            $file_put_contents = '<font color=red>[×]关闭</font>';
            $err++;
        }
        if (function_exists('imagettftext')) {
            $imagettftext = '<font color=green>[√]开启</font> ';
        } else {
            $imagettftext = '<font color=red>[×]关闭</font>';
            $err++;
        }
        if (function_exists('pcntl_alarm')) {
            $pcntl_alarm = '<font color=green>[√]开启</font> ';
        } else {
            $pcntl_alarm = '<font color=red>[×]关闭</font>';
            $err++;
        }
        if (function_exists('proc_open')) {
            $proc_open = '<font color=green>[√]开启</font> ';
        } else {
            $proc_open = '<font color=red>[×]关闭</font>';
            $err++;
        }
        if (function_exists('pcntl_signal')) {
            $pcntl_signal = '<font color=green>[√]开启</font> ';
        } else {
            $pcntl_signal = '<font color=red>[×]关闭</font>';
            $err++;
        }
        View::assign([
            'max_execution_time' => $max_execution_time,
            'allow_reference' => $allow_reference,
            'phpv' => $phpv,
            'allow_url_fopen' => $allow_url_fopen,
            'safe_mode' => $safe_mode,
            'gd' => $gd,
            'mysql' => $mysql,
            'uploadSize' => $uploadSize,
            'redis' => $redis,
            'session' => $session,
            'swoole' => $swoole,
            'curl' => $curl,
            'bcmath' => $bcmath,
            'openssl' => $openssl,
            'finfo_open' => $finfo_open,
            'file_put_contents' => $file_put_contents,
            'imagettftext' => $imagettftext,
            'folder' => $folder,
            'zip' => $zip,
            'proc_open' => $proc_open,
            'pcntl_alarm' => $pcntl_alarm,
            'pcntl_signal' => $pcntl_signal,
        ]);
        return View::fetch('/install/step2', ['err' => $err]);
    }

    /**
     * TODO 3 数据库填写表单
     * @return string
     * @author Qinii
     * @day 2020-07-15
     */
    public function databases()
    {
        return View::fetch('/install/step3');
    }
    /**
     * TODO 4 安装数据库
     * @return string
     * @author Qinii
     * @day 2020-07-16
     */
    public function create()
    {
        $data = $this->request->params(['dbhost', 'dbport', 'dbname', 'dbuser', 'dbpw', ['dbprefix','eb_'], 'manager', 'manager_pwd', ['rbhost', '127.0.0.1'], ['rbport', 6379], 'rbpw', ['rbselect', 0], 'demo']);
        $mysql = $this->checkDatabsaces($data);
        if ($mysql !== 1) throw new ValidateException('数据库链接失败' . $mysql);
        return View::fetch('/install/step4', ['data' => $data]);
    }

    /**
     * TODO 5 安装完成
     * @return string
     * @author Qinii
     * @day 2020-07-16
     */
    public function end()
    {
        $ip = $this->get_client_ip();
        $server = $this->request->server();
        $host = $server['HTTP_HOST'];
        $version = get_crmeb_version('未知');
        $this->installlog();
        @touch(__DIR__ . '/../../install/install.lock');
        $this->unzip();
        return View::fetch('/install/step5', [
            'host' => $host,
            'ip' => $ip,
            'version' => $version,
            'merchant' => Config::get('admin.merchant_prefix'),
            'system' => Config::get('admin.admin_prefix'),
        ]);
    }

    /**
     * TODO 链接数据库 读取sql
     * @param $n
     * @return array
     * @author Qinii
     * @day 2020-07-16
     */
    public function perform($n)
    {
        $data = $this->request->param();
        $dbName = strtolower(trim($data['dbname']));
        $conn = @mysqli_connect($data['dbhost'], $data['dbuser'], $data['dbpw'], NULL, $data['dbport']);
        if (mysqli_connect_errno($conn)) {
            throw new ValidateException("连接数据库失败!" . mysqli_connect_error($conn));
        }
        if (!mysqli_select_db($conn, $dbName)) {
            //创建数据时同时设置编码
            if (!mysqli_query($conn, "CREATE DATABASE IF NOT EXISTS `" . $dbName . "` DEFAULT CHARACTER SET utf8;")) {
                throw new ValidateException('数据库 ' . $dbName . ' 不存在，也没权限创建新的数据库！');
            }
        }
        mysqli_select_db($conn, $dbName);
        mysqli_query($conn, 'SET NAMES utf8;');
        //读取数据文件
        $sqldata = file_get_contents(__DIR__ . '/../../install/' . $this->sqlFile);
        $sqlFormat = $this->sql_split($sqldata, $data['dbprefix']);
        $counts = count($sqlFormat);
        if ($n <= $counts && isset($sqlFormat[$n])) {
            $sql = trim($sqlFormat[$n]);
            $message = $this->install($sql, $conn, $data['dbprefix']);
            $n++;
        } else {
            if ($data['demo'] == '') $this->clear($conn, $data['dbprefix']);
            $this->setConfig($data);
            $message = $this->setDatabase($conn, $data);
            $n = -1;
        }
        return [
            'n' => $n,
            'msg' => $message
        ];
    }


    /**
     * TODO 执行数据库文件安装
     * @param $sql
     * @param $conn
     * @param $dbPrefix
     * @return string
     * @author Qinii
     * @day 2020-07-16
     */
    public function install($sql, $conn, $dbPrefix)
    {
        // 建表
        if (strstr($sql, 'CREATE TABLE')) {
            preg_match('/CREATE TABLE `eb_([^ ]*)`/is', $sql, $matches);
            mysqli_query($conn, "DROP TABLE IF EXISTS `$matches[1]");
            $sql = str_replace('`eb_', '`' . $dbPrefix, $sql);//替换表前缀
            $ret = mysqli_query($conn, $sql);
            if ($ret) {
                $message = '<li><span class="correct_span">&radic;</span>创建数据表[' . $dbPrefix . $matches[1] . ']完成!<span style="float: right;">' . date('Y-m-d H:i:s') . '</span></li> ';
            } else {
                $message = '<li><span class="correct_span error_span">&radic;</span>创建数据表[' . $dbPrefix . $matches[1] . ']失败!<span style="float: right;">' . date('Y-m-d H:i:s') . '</span></li>';
            }

        } //插入数据
        else {
            $message = '';
            if (trim($sql) !== '') {
                $sql = str_replace('`eb_', '`' . $dbPrefix, $sql);//替换表前缀
                $sql = str_replace('https://mer1.crmeb.net', $this->installHost , $sql);
                $sql = str_replace('https:\\\/\\\/mer1.crmeb.net', $this->_url , $sql);
                $ret = mysqli_query($conn, $sql);
                $sql = htmlspecialchars($sql);
                $msg = substr($sql, 0, 30);
                if ($ret) {
                    $message = '<li><span class="correct_span">&radic;</span>执行 [' . $msg . '...]成功!<span style="float: right;">' . date('Y-m-d H:i:s') . '</span></li> ';
                } else {
                    $message = '<li><span class="correct_span error_span">&radic;</span>执行[' . $msg . '..]失败!<span style="float: right;">' . date('Y-m-d H:i:s') . '</span></li>';
                }
            }
        }
        return $message;
    }

    /**
     * TODO 清除测试数据
     * @param $conn
     * @param $dbPrefix
     * @author Qinii
     * @day 2020-07-16
     */
    public function clear($conn, $dbPrefix)
    {
        $result = mysqli_query($conn, "show tables");
        $tables = mysqli_fetch_all($result);//参数MYSQL_ASSOC、MYSQLI_NUM、MYSQLI_BOTH规定产生数组类型
        $bl_table = self::DEMO_SQL_TABLE;
        foreach ($bl_table as $k => $v) {
            $bl_table[$k] = str_replace('eb_', $dbPrefix, $v);
        }

        foreach ($tables as $key => $val) {
            if (!in_array($val[0], $bl_table)) {
                mysqli_query($conn, "truncate table " . $val[0]);
            }
        }
    }

    /**
     * TODO 创建.env文件
     * @param $data
     * @author Qinii
     * @day 2020-07-16
     */
    public function setConfig($data)
    {
        //读取配置文件，并替换真实配置数据1
        $strConfig = file_get_contents(__DIR__ . '/../../install/' . $this->configFile);
        //'dbhost', 'dbport', 'dbname', 'dbuser', 'dbpw', 'dbprefix', 'manager', 'manager_pwd', ['rbhost', '127.0.0.1'], ['rbport', 6379], 'rbpw', ['rbselect', 0]
        $strConfig = str_replace('#DB_HOST#', $data['dbhost'], $strConfig);
        $strConfig = str_replace('#DB_NAME#', $data['dbname'], $strConfig);
        $strConfig = str_replace('#DB_USER#', $data['dbuser'], $strConfig);
        $strConfig = str_replace('#DB_PWD#', $data['dbpw'], $strConfig);
        $strConfig = str_replace('#DB_PORT#', $data['dbport'], $strConfig);
        $strConfig = str_replace('#DB_PREFIX#', $data['dbprefix'], $strConfig);
        $strConfig = str_replace('#DB_CHARSET#', 'utf8', $strConfig);
        //redis数据库信息
        $strConfig = str_replace('#RB_HOST#', $data['rbhost'], $strConfig);
        $strConfig = str_replace('#RB_PORT#', $data['rbport'], $strConfig);
        $strConfig = str_replace('#RB_PWD#', $data['rbpw'], $strConfig);
        $strConfig = str_replace('#RB_SELECT#', $data['rbselect'], $strConfig);
        $strConfig = str_replace('#APP_KEY#', md5(time() . random_int(10000000, 99999999)), $strConfig);
        @chmod(__DIR__ . '/../../.env', 0777);
        @file_put_contents(__DIR__ . '/../../.env', $strConfig); //数据库配置文件的地址
    }

    /**
     * TODO 修改后台管理员用户
     * @param $conn
     * @param $data
     * @return string
     * @author Qinii
     * @day 2020-07-16
     */
    public function setDatabase($conn, $data)
    {
        $time = date('Y-m-d H:i:s');
        $ip = $this->get_client_ip();
        $ip = empty($ip) ? "0.0.0.0" : $ip;
        $password = password_hash(trim($data['manager_pwd']), PASSWORD_BCRYPT);
        mysqli_query($conn, "truncate table {$data['dbprefix']}system_admin");
        $addadminsql = "INSERT INTO `{$data['dbprefix']}system_admin` (`admin_id`, `account`, `pwd`, `real_name`, `roles`, `last_ip`, `last_time`, `create_time`, `login_count`, `level`, `status`, `is_del`) VALUES
(1, '" . $data['manager'] . "', '" . $password . "', '', '1', '" . $ip . "','$time' , '$time', 0, 0, 1, 0)";
        $res = mysqli_query($conn, $addadminsql);
        if ($res) {
            $message = '成功添加管理员<br />成功写入配置文件<br>安装完成．';
        } else {
            $message = '添加管理员失败<br />成功写入配置文件<br>安装完成．';
        }
        return $message;
    }

    /**
     * TODO 检测数据库链接是否成功以及版本
     * @param $data
     * @return false|int|string
     * @author Qinii
     * @day 2020-07-16
     */
    public function checkDatabsaces($data)
    {
        $dbName = strtolower(trim($data['dbname']));
        $conn = @mysqli_connect($data['dbhost'], $data['dbuser'], $data['dbpw'], NULL, $data['dbport']);
        if (mysqli_connect_errno($conn)) return 0;
        $result = mysqli_query($conn, "SELECT @@global.sql_mode");
        $result = $result->fetch_array();
        $version = mysqli_get_server_info($conn);
        if ($version < 5.6) return (json_encode(-4));

        if (strstr($result[0], 'STRICT_TRANS_TABLES') || strstr($result[0], 'STRICT_ALL_TABLES') || strstr($result[0], 'TRADITIONAL') || strstr($result[0], 'ANSI'))
            return ($version < 8.0) ? -1 : -2;

        $result = mysqli_query($conn, "select count(table_name) as c from information_schema.`TABLES` where table_schema='$dbName'");
        $result = $result->fetch_array();
        if ($result['c'] > 0) return -3;
        return 1;
    }

    /**
     * TODO 验证数据库及redis是否正常
     * @return false|int|string
     * @author Qinii
     * @day 2020-07-15
     */
    public function databasesCheck()
    {
        $data = $this->request->params(['dbhost', 'dbport', 'dbname', 'dbuser', 'dbpw',['dbprefix','eb_'], ['rbhost', '127.0.0.1'], ['rbport', 6379], 'rbpw', ['rbselect', 0]]);
        // mysql 检测
        $mysql = $this->checkDatabsaces($data);
        if ($mysql !== 1) return $mysql;
        // redis检测
        try {
            $redis = new Redis();
            $redis->connect($data['rbhost'], $data['rbport']);
            if ($data['rbpw']) $redis->auth($data['rbpw']);
            if ($data['rbselect']) $redis->select($data['rbselect']);
            $res = $redis->set('install', 1, 10);
            return $res ? 1 : -5;
        } catch (Throwable $e) {
            return -5;
        }
    }

    /**
     * TODO 安装记录文件生成
     * @author Qinii
     * @day 2020-07-16
     */
    public function installlog()
    {
        $mt_rand_str = $this->sp_random_string(6);
        $str_constant = "<?php" . PHP_EOL . "define('INSTALL_DATE'," . time() . ");" . PHP_EOL . "define('SERIALNUMBER','" . $mt_rand_str . "');";
        @file_put_contents(__DIR__ . '/../../.constant', $str_constant);
    }

    /**
     * TODO 随机字符串
     * @param int $len
     * @return string
     * @author Qinii
     * @day 2020-07-16
     */
    public function sp_random_string($len = 8)
    {
        $chars = array(
            "a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k",
            "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v",
            "w", "x", "y", "z", "A", "B", "C", "D", "E", "F", "G",
            "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R",
            "S", "T", "U", "V", "W", "X", "Y", "Z", "0", "1", "2",
            "3", "4", "5", "6", "7", "8", "9"
        );
        $charsLen = count($chars) - 1;
        shuffle($chars);    // 将数组打乱
        $output = "";
        for ($i = 0; $i < $len; $i++) {
            $output .= $chars[mt_rand(0, $charsLen)];
        }
        return $output;
    }

    /**
     * TODO 格式化mysql文件
     * @param $sql
     * @param $tablepre
     * @return array
     * @author Qinii
     * @day 2020-07-16
     */
    public function sql_split($sql, $tablepre)
    {

        if ($tablepre != "tp_")
            $sql = str_replace("tp_", $tablepre, $sql);

        $sql = preg_replace("/TYPE=(InnoDB|MyISAM|MEMORY)( DEFAULT CHARSET=[^; ]+)?/", "ENGINE=\\1 DEFAULT CHARSET=utf8", $sql);

        $sql = str_replace("\r", "\n", $sql);
        $ret = array();
        $num = 0;
        $queriesarray = explode(";\n", trim($sql, "\xEF\xBB\xBF"));
        unset($sql);
        foreach ($queriesarray as $query) {
            $ret[$num] = '';
            $queries = explode("\n", trim($query));
            $queries = array_filter($queries);
            foreach ($queries as $query) {
                $str1 = substr($query, 0, 1);
                if ($str1 != '#' && $str1 != '-')
                    $ret[$num] .= $query;
            }
            $num++;
        }
        return $ret;
    }

    /**
     * TODO 获取ip地址
     * @return string|null
     * @author Qinii
     * @day 2020-07-16
     */
    public function get_client_ip()
    {
        $server = $this->request->server();
        if (isset($server['REMOTE_ADDR']))
            $ip = $server['REMOTE_ADDR'];
        // IP地址合法验证
        $ip = (false !== ip2long($ip)) ? $ip : '0.0.0.0';
        return $ip;
    }

    /**
     *  生成基础文件
     * @Author:Qinii
     * @Date: 2020/8/31
     */
    public function unzip()
    {
        update_crmeb_compiled();
    }
}
