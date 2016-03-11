<?php

namespace pulgins;
/**
 * Class Config
 * @package pulgins
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
     * @param $configPath 配置文件放置的目录
     * @return array|mixed
     */
    public static function load($configPath)
    {
        $files = self::getDirFile($configPath, "/.php$/");
        $config = array();
        if (!empty($files)) {
            foreach ($files as $file) {
                $config += include "{$file}";
            }
        }
        self::$config = $config;
        self::$configPath = $configPath;

        return $config;
    }

    /**
     * 递归获取目录下的文件
     * @param $dir
     * @param string $filter
     * @param array $result
     * @param bool $deep
     * @return array
     */
    public static function getDirFile($dir, $filter = '', &$result = array(), $deep = false)
    {
        $files = new \DirectoryIterator($dir);
        foreach ($files as $file) {
            $filename = $file->getFilename();
            if ($filename[0] === '.') {
                continue;
            }
            //过滤文件移动到下面  change by ahuo 2013-09-11 16:23
            //if (!empty($filter) && !\preg_match($filter, $filename)) {
            //  continue;
            //}

            if ($file->isDir()) {
                self::getDirFile($dir . DIRECTORY_SEPARATOR . $filename, $filter, $result, $deep);
            } else {
                if(!empty($filter) && !\preg_match($filter,$filename)){
                    continue;
                }
                if ($deep) {
                    $result[$dir] = $filename;
                } else {
                    $result[] = $dir . DIRECTORY_SEPARATOR . $filename;
                }
            }
        }
        return $result;
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
