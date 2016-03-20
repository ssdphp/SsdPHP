<?php
// +----------------------------------------------------------------------
// | api.uarein.com
// +----------------------------------------------------------------------
// | Copyright (c) 2015 http://www.uarein.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: xiaohuihui <598550105@qq.com> //
// +----------------------------------------------------------------------
namespace pulgins;

use SsdPHP\SsdPHP;

class Tool{

    /**解析ini配置文件
     * @param $file
     * @return array
     */
    public static function parse_ini($file){
        $config=array();
        if(isset($file) && file_exists($file)) {
            $config = parse_ini_file($file,true);
        }
        return $config;
    }

    /**
     * 获取配置信息
     * @author  xiaohuihui  <598550105@qq.com>
     * @param string|object|array $name 配置变量
     * @param string $value 配置值
     * @param string $default 默认值
     * @return mixed
     */
    public static function Config($name=null,$value=null,$default=null){
        static $_config = array();
        // 无参数时获取所有
        if (empty($name)) {
            return $_config;
        }
        // 优先执行设置获取或赋值
        if (is_string($name)) {
            if (!strpos($name, '.')) {
                $name = strtoupper($name);
                if (is_null($value))
                    return isset($_config[$name]) ? $_config[$name] : $default;
                $_config[$name] = $value;
                return null;
            }
            // 二维数组设置和获取支持
            $name = explode('.', $name);
            $name[0]   =  strtoupper($name[0]);
            if (is_null($value))
                return isset($_config[$name[0]][$name[1]]) ? $_config[$name[0]][$name[1]] : $default;
            $_config[$name[0]][$name[1]] = $value;
            return null;
        }

        // 批量设置输入数组
        if(is_array($name)){
            if(!empty($name)){
                $_config = array_merge($_config, array_change_key_case($name,CASE_UPPER));
                return null;
            }
        }
        // 批量设置对象
        if(is_object($name)){
            $name = json_decode(json_encode($name),true);//将对象转为数组
            if(!empty($name)){
                $_config = array_merge($_config, array_change_key_case($name,CASE_UPPER));
            }
        }
        return null; // 避免非法参数
    }

    /**
     * utf-8字符的长度获取
     * @author  xiaohuihui  <598550105@qq.com>
     * @param $str
     * @param string $encoding 字符编码，默认是UTF-8
     * @return int
     */
    public static function utf8_strlen($str,$encoding="UTF-8"){
        $count=0;
        if(function_exists('mb_strlen')){
            return $count = mb_strlen($str,$encoding);
        }else{
            die('不支持mb_strlen函数，请打开php_mbstring.dll|so');
        }

    }

    /**
     * 非常规密码md5加密方式
     * @author  xiaohuihui  <598550105@qq.com>
     * @param $str
     * @param string $key 加密key
     * @return string
     */
    public static function pwd_md5($str,$key='$key'){

        return '' === $str ? '' : md5(sha1($str) . $key);
    }

    /**
     * 不区分大小写的in_array实现
     * @author  xiaohuihui  <598550105@qq.com>
     * @param $value
     * @param $array
     * @return bool
     */
    public static function in_array_case($value,$array){
        return in_array(strtolower($value),array_map('strtolower',$array));
    }

    /**
     * 获取输入参数 支持过滤和默认值
     * 使用方法:
     * <code>
     * I('id',0); 获取id参数 自动判断get或者post
     * I('post.name','','htmlspecialchars'); 获取$_POST['name']
     * I('get.'); 获取$_GET
     * </code>
     * @param string $name 变量的名称 支持指定类型
     * @param mixed $default 不存在的时候默认值
     * @param mixed $filter 参数过滤方法
     * @param mixed $datas 要获取的额外数据源
     * @return mixed
     */
    public static function I($name,$default='',$filter=null,$datas=null) {
        static $_PUT	=	null;
        if(strpos($name,'/')){ // 指定修饰符
            list($name,$type) 	=	explode('/',$name,2);
        }
        if(strpos($name,'.')) { // 指定参数来源
            list($method,$name) =   explode('.',$name,2);
        }else{ // 默认为自动判断
            $method =   'param';
        }
        switch(strtolower($method)) {
            case 'get'     :
                $input =& $_GET;
                break;
            case 'post'    :
                $input =& $_POST;
                break;
            case 'put'     :
                if(is_null($_PUT)){
                    parse_str(file_get_contents('php://input'), $_PUT);
                }
                $input 	=	$_PUT;
                break;
            case 'param'   :
                switch($_SERVER['REQUEST_METHOD']) {
                    case 'POST':
                        $input  =  $_POST;
                        break;
                    case 'PUT':
                        if(is_null($_PUT)){
                            parse_str(file_get_contents('php://input'), $_PUT);
                        }
                        $input 	=	$_PUT;
                        break;
                    default:
                        $input  =  $_GET;
                }
                break;
            case 'path'    :
                $input  =   array();
                if(!empty($_SERVER['PATH_INFO'])){
                    $depr   =   SsdPHP::getSplitFlag();
                    $input  =   explode($depr,trim($_SERVER['PATH_INFO'],$depr));
                }
                break;
            case 'request' :
                $input =& $_REQUEST;
                break;
            case 'session' :
                $input =& $_SESSION;
                break;
            case 'cookie'  :
                $input =& $_COOKIE;
                break;
            case 'server'  :
                $input =& $_SERVER;
                break;
            case 'globals' :
                $input =& $GLOBALS;
                break;
            case 'data'    :
                $input =& $datas;
                break;
            default:
                return null;
        }
        if(''==$name) { // 获取全部变量
            $data       =   $input;
            $filters    =   isset($filter)?$filter:base_constant::DEFAULT_FILTER;
            if($filters) {
                if(is_string($filters)){
                    $filters    =   explode(',',$filters);
                }
                foreach($filters as $filter){
                    $data   =   self::array_map_recursive($filter,$data); // 参数过滤
                }
            }
        }elseif(isset($input[$name])) { // 取值操作
            $data       =   $input[$name];
            $filters    =   isset($filter)?$filter:base_constant::DEFAULT_FILTER;
            if($filters) {
                if(is_string($filters)){
                    if(0 === strpos($filters,'/')){
                        if(1 !== preg_match($filters,(string)$data)){
                            // 支持正则验证
                            return   isset($default) ? $default : null;
                        }
                    }else{
                        $filters    =   explode(',',$filters);
                    }
                }elseif(is_int($filters)){
                    $filters    =   array($filters);
                }

                if(is_array($filters)){
                    foreach($filters as $filter){
                        if(function_exists($filter)) {
                            $data   =   is_array($data) ? self::array_map_recursive($filter,$data) : $filter($data); // 参数过滤
                        }else{
                            $data   =   filter_var($data,is_int($filter) ? $filter : filter_id($filter));
                            if(false === $data) {
                                return   isset($default) ? $default : null;
                            }
                        }
                    }
                }
            }
            if(!empty($type)){
                switch(strtolower($type)){
                    case 'a':	// 数组
                        $data 	=	(array)$data;
                        break;
                    case 'd':	// 数字
                        $data 	=	(int)$data;
                        break;
                    case 'f':	// 浮点
                        $data 	=	(float)$data;
                        break;
                    case 'b':	// 布尔
                        $data 	=	(boolean)$data;
                        break;
                    case 's':   // 字符串
                    default:
                        $data   =   (string)$data;
                }
            }
        }else{ // 变量默认值
            $data       =    isset($default)?$default:null;
        }
        is_array($data) && array_walk_recursive($data,'self::safe_filter');
        return $data;
    }

    /**
     * 过滤的其他规则
     * @author  xiaohuihui  <598550105@qq.com>
     * @param $value
     */
    private static function safe_filter(&$value){
        // TODO 其他安全过滤

        // 过滤查询特殊字符
        if(preg_match('/^(INSERT|UPDATE|DELETE|SELECT)$/i',$value)){
            $value .= ' ';
        }
    }

    /**
     * 过滤I函数使用到
     * @author  xiaohuihui  <598550105@qq.com>
     * @param $filter
     * @param $data
     * @return array
     */
    private static function array_map_recursive($filter, $data) {
        $result = array();
        foreach ($data as $key => $val) {
            $result[$key] = is_array($val)
                ? self::array_map_recursive($filter, $val)
                : call_user_func($filter, $val);
        }
        return $result;
    }

    /**
     * 记录和统计时间（微秒）和内存使用情况
     * 使用方法:
     * <code>
     * G('begin'); // 记录开始标记位
     * // ... 区间运行代码
     * G('end'); // 记录结束标签位
     * echo G('begin','end',6); // 统计区间运行时间 精确到小数后6位
     * echo G('begin','end','m'); // 统计区间内存使用情况
     * 如果end标记位没有定义，则会自动以当前作为标记位
     * 其中统计内存使用需要 MEMORY_LIMIT_ON 常量为true才有效
     * </code>
     * @param $start 开始标签
     * @param $end 结束标签
     * @param integer|string $dec 小数位或者m
     * @return mixed
     */
    public static function G($start,$end='',$dec=4){
        static $_info       =   array();

        static $_mem        =   array();
        if(is_float($end)) { // 记录时间
            $_info[$start]  =   $end;
        }elseif(!empty($end)){ // 统计时间和内存使用
            if(!isset($_info[$end])) $_info[$end]       =  microtime(TRUE);
            if($dec=='m'){
                if(!isset($_mem[$end])) $_mem[$end]     =  memory_get_usage();
                return number_format(($_mem[$end]-$_mem[$start])/1024);
            }else{
                return number_format(($_info[$end]-$_info[$start]),$dec);
            }

        }else{ // 记录时间和内存使用
            $_info[$start]  =  microtime(TRUE);
            $_mem[$start]   =  memory_get_usage();
        }
        return null;
    }


    public static function uri_parse(){

        $url_suffix = self::Config("URL_HTML_SUFFIX")
            ? self::Config("URL_HTML_SUFFIX") :'.html';
        if(isset($_SERVER['PATH_INFO'])){
            $ext = strtolower(pathinfo($_SERVER['PATH_INFO'],PATHINFO_EXTENSION));
            $_SERVER['PATH_INFO'] = preg_replace($url_suffix? '/\.('.trim($url_suffix,'.').')$/i' : '/\.'.$ext.'$/i', '', $_SERVER['PATH_INFO']);
            $path = $_SERVER['PATH_INFO'];
            unset($_SERVER['PATH_INFO']);
            if(isset($_GET['PATH_INFO'])){
                unset($_GET['PATH_INFO']);
            }
        }elseif(isset($_GET['PATH_INFO'])){
            $ext = strtolower(pathinfo($_GET['PATH_INFO'],PATHINFO_EXTENSION));
            $_GET['PATH_INFO'] = preg_replace($url_suffix ? '/\.('.trim($url_suffix,'.').')$/i' : '/\.'.$ext.'$/i', '', $_GET['PATH_INFO']);
            $path = $_GET['PATH_INFO'];
            unset($_GET['PATH_INFO']);
        }elseif(PHP_SAPI=='cli'){
            $_SERVER['PATH_INFO']=isset($_SERVER['argv'][1]) ? $_SERVER['argv'][1] : '';
        }

        $var = array();
        if(isset($path) && !empty($path)){
            SsdPHP::setPathInfo($path);
            $urlAry = explode(SsdPHP::getSplitFlag(),trim($path,SsdPHP::getSplitFlag()));
            if(!empty($urlAry)){
                if(isset($urlAry[0])){
                    unset($urlAry[0]);
                }
                if(isset($urlAry[1])){
                    unset($urlAry[1]);
                }
                if(isset($urlAry[2])){
                    unset($urlAry[2]);
                }
                //提取pathinfo url
                preg_replace_callback('/(\w+)\/([^\/]+)/', function($match) use(&$var){$var[$match[1]]=strip_tags($match[2]);}, implode(SsdPHP::getSplitFlag(),$urlAry));
            }
        }
        $_GET   =  array_merge($var,$_GET);
        $_REQUEST = array_merge($_POST,$_GET);
    }
}