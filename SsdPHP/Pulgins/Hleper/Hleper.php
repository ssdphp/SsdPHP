<?php

namespace SsdPHP\Pulgins\Hleper;

class Hleper{

    /**
     * utf-8字符的长度获取
     * @author  xiaohuihui  <598550105@qq.com>
     * @param $str
     * @param string $encoding 字符编码，默认是UTF-8
     * @return int
     */
    public static function utf8_strlen($str="",$encoding="UTF-8"){
        if(function_exists('mb_strlen')){
            return $count = mb_strlen($str,$encoding);
        }else{
            throw new \Exception('不支持mb_strlen函数，请打开php_mbstring.dll|so');
        }

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
}