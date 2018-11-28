<?php

namespace SsdPHP\Core;

use SsdPHP\File\Dir;
use SsdPHP\SsdPHP;

/**
 * Class Config
 * @package SsdPHP\Core
 */
class Config
{
    /**
     * @var array
     */
    private static $config = [];
    /**
     * @var string
     */
    private static $configPath="";
    /**
     * 加载配置文件
     * @param string $configPath 配置文件放置的目录|默认获取/config目录
     */
    public static function load($configPath="")
    {
        if(empty($configPath)){
            $configPath = SsdPHP::getRootPath().'Config';
        }
        $tmpfile = sys_get_temp_dir().md5(realpath($configPath));
        if(!is_dir($configPath)){
            throw new \Exception("no dir: $configPath");
        }
        fileatime($configPath);
        if(is_file($tmpfile) && filemtime($tmpfile)>=filemtime($configPath) && SsdPHP::getDebug()==false){
            self::$config = include $tmpfile;
            return;
        }
        $files = Dir::tree($configPath, "/.php$/");
        $config = array();
        if (!empty($files)) {
            foreach ($files as $file) {
                $config += include "{$file}";
            }
        }
        self::$config = $config;
        self::$configPath = $configPath;
        file_put_contents($tmpfile,"<?php return ".var_export($config,true).";?>",LOCK_EX);
        return;
    }


    /**
     * 多个配置文件加载
     * @param array $files
     * @return array|mixed
     */
    public static function loadFiles(array $files)
    {
        $config = array();
        foreach ($files as $file) {
            $config += include "{$file}";
        }
        self::$config = $config;
        return $config;
    }

    /**
     * 获取配置
     * @param $key
     * @param $default 返回的默认值
     * @param $throw 是否抛出异常,默认false
     * @return mixed
     * @throws \Exception
     */
    public static function get($key, $default = null, $throw = false)
    {
        $result = isset(self::$config[$key]) ? self::$config[$key] : $default;
        if ($throw && is_null($result)) {
            throw new \Exception("{key} config empty");
        }
        return $result;
    }

    public static function set($key, $value, $set = true)
    {
        if ($set) {
            self::$config[$key] = $value;
        } else {
            if (empty(self::$config[$key])) {
                self::$config[$key] = $value;
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
        $result = isset(self::$config[$key][$field]) ? self::$config[$key][$field] : $default;
        if ($throw && is_null($result)) {
            throw new \Exception("{key} config empty");
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
            self::$config[$key][$field] = $value;
        } else {
            if (empty(self::$config[$key][$field])) {
                self::$config[$key][$field] = $value;
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
        return self::$config;
    }
}
