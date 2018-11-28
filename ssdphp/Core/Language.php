<?php

namespace SsdPHP\Core;

use SsdPHP\File\Dir;
use SsdPHP\SsdPHP;

/**
 * Class LanguagePath
 * @package SsdPHP\Core
 */
class Language
{
    /**
     * @var array
     */
    private static $LanguageAry = [];
    /**
     * @var string
     */
    private static $LanguagePath="";

    /**
     * 加载资源包文件
     * 框架运行前设置
     * @param string $srcPath
     * @param string $defaultModel 默认使用哪个model
     * @param string $Lang 语音类型，zh,en
     * @param bool $is_zh_force 是否强制使用中文，default=true
     * @param bool $is_en_force 是否强制使用英文，default=false
     */
    public static function load($srcPath="",$defaultModel="",$Lang="en",$is_zh_force=true,$is_en_force=false)
    {
        if($is_zh_force == true){
            $Lang='zh';
        }elseif($is_en_force == true){
            $Lang='en';
        }elseif(!empty($_SERVER['HTTP_ACCEPT_LANGUAGE'])){
            $langstr = $_SERVER['HTTP_ACCEPT_LANGUAGE']."";
            switch (strtolower($langstr[0].$langstr[1])){
                case 'zh':$Lang='zh';break;
                case 'en':$Lang='en';break;
                default  :$Lang='zh';
            }
        }
        if($srcPath == ""){
            $srcPath = SsdPHP::getRootPath()."resources/lang/".$Lang."/";
        }
        if(!is_dir($srcPath)){
            return ;
        }
        self::$LanguagePath = $srcPath;
        $tmpfile = sys_get_temp_dir().md5(realpath($srcPath.$defaultModel));
        if(SsdPHP::getDebug() == false){
            fileatime($srcPath);
            if(is_file($tmpfile) && filemtime($tmpfile)>=filemtime($srcPath)){
                self::$LanguageAry = include $tmpfile;
            }
        }

        $files = Dir::tree($srcPath, "/.php$/");
        $Language = array();

        if($defaultModel != ""){
            $langFile = $srcPath.$defaultModel.".php";
            if(is_file($langFile)){
                $Language = include "{$langFile}";
            }

        } elseif (!empty($files)) {
            foreach ($files as $file) {
                $Language += include "{$file}";
            }

        }
        if(!empty($Language)){
            self::$LanguageAry = $Language;
            file_put_contents($tmpfile,"<?php return ".var_export($Language,true).";?>",LOCK_EX);
        }
    }



    /**
     * 获取语言包
     * @param $key
     * @param $default 返回的默认值
     * @param $throw 是否抛出异常,默认false
     * @return mixed
     * @throws \Exception
     */
    public static function get($key, $default = null, $throw = false)
    {
        $result = isset(self::$LanguageAry[$key]) ? self::$LanguageAry[$key] : $default;
        if ($throw && is_null($result)) {
            throw new \Exception("{key} Language empty");
        }
        return $result;
    }

    public static function set($key, $value, $set = true)
    {
        if ($set) {
            self::$LanguageAry[$key] = $value;
        } else {
            if (empty(self::$LanguageAry[$key])) {
                self::$LanguageAry[$key] = $value;
            }
        }

        return true;
    }

    /**
     * 获取二维数组数据
     * @param $key
     * @param $field
     * @param $default
     * @param $throw 是否抛出异常,默认false
     * @return mixed
     * @throws \Exception
     */
    public static function getField($key, $field, $default = null, $throw = false)
    {
        $result = isset(self::$LanguageAry[$key][$field]) ? self::$LanguageAry[$key][$field] : $default;
        if ($throw && is_null($result)) {
            throw new \Exception("{key} Language empty");
        }
        return $result;
    }

    /**
     * 设置二位数组数据
     * @param $key
     * @param $field
     * @param $value
     * @param bool $set
     * @return bool
     */
    public static function setField($key, $field, $value, $set = true)
    {
        if ($set) {
            self::$LanguageAry[$key][$field] = $value;
        } else {
            if (empty(self::$LanguageAry[$key][$field])) {
                self::$LanguageAry[$key][$field] = $value;
            }
        }

        return true;
    }

    /**
     * 返回所有数组
     * @return array
     */
    public static function all()
    {
        return self::$LanguageAry;
    }
}
