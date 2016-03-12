<?php

namespace SsdPHP\Pulgins\Session;
use SsdPHP\Core\Factory as SFactory,
    SsdPHP\Core\Config as SConfig;

class Factory
{

    private static $isStart = false;
    private static $prefix  = "";

    public static function getInstance($adapter = 'Redis', $config = null)
    {
        $className = __NAMESPACE__ . "\\Adaptor\\{$adapter}";
        return SFactory::getInstance($className, $config);
    }

    public static function Start( $sessionType="" ,$config=null){

        if(self::$isStart === false){

            if(empty($config)){
                $config = SConfig::get('Session');
            }
            $lifetime = 0;
            if(!empty($config['new_cache_expire'])) {
                session_cache_expire($config['new_cache_expire']);
                $lifetime = $config['new_cache_expire'] * 60;
            }
            $path = empty($config['path']) ? '/' : $config['path'];
            $domain = empty($config['domain']) ? '' : $config['domain'];
            $secure = empty($config['secure']) ? false : $config['secure'];
            $httponly = !isset($config['httponly']) ? true : $config['httponly'];
            session_set_cookie_params($lifetime, $path, $domain, $secure, $httponly);

            $sessionName = empty($config['session_name']) ? 'SSDPHPSESSID' : $config['session_name'];
            session_name($sessionName);

            if(!empty($_GET[$sessionName])) {
                session_id($_GET[$sessionName]);
            }elseif(!empty($_SERVER[$sessionName])) {
                session_id($_SERVER[$sessionName]);
            }

            if (!empty($sessionType)) {
                $handler = self::getInstance($sessionType, $config);
                session_set_save_handler(
                    array($handler, 'open'),
                    array($handler, 'close'),
                    array($handler, 'read'),
                    array($handler, 'write'),
                    array($handler, 'destroy'),
                    array($handler, 'gc')
                );
            }
            session_start();
            self::$isStart = true;
        }
    }

    /**
     * session设置
     *
     * @param string $name session名称
     * @param mixed $value session值
     * @param string $prefix 作用域（前缀）
     * @return void
     */
    public static function set($name, $value = '', $prefix = '')
    {
        $prefix = $prefix ? $prefix : self::$prefix;
        if (strpos($name, '.')) {
            // 二维数组赋值
            list($name1, $name2) = explode('.', $name);
            if ($prefix) {
                $_SESSION[$prefix][$name1][$name2] = $value;
            } else {
                $_SESSION[$name1][$name2] = $value;
            }
        } elseif ($prefix) {
            $_SESSION[$prefix][$name] = $value;
        } else {
            $_SESSION[$name] = $value;
        }
    }

    /**
     * session获取
     *
     * @param string $name session名称
     * @param string $prefix 作用域（前缀）
     * @return mixed
     */
    public static function get($name = '', $prefix = '')
    {
        $prefix = $prefix ? $prefix : self::$prefix;
        if ('' == $name) {
            // 获取全部的session
            $value = $prefix ? $_SESSION[$prefix] : $_SESSION;
        } elseif ($prefix) {
            // 获取session
            if (strpos($name, '.')) {
                list($name1, $name2) = explode('.', $name);
                $value               = isset($_SESSION[$prefix][$name1][$name2]) ? $_SESSION[$prefix][$name1][$name2] : null;
            } else {
                $value = isset($_SESSION[$prefix][$name]) ? $_SESSION[$prefix][$name] : null;
            }
        } else {
            if (strpos($name, '.')) {
                list($name1, $name2) = explode('.', $name);
                $value               = isset($_SESSION[$name1][$name2]) ? $_SESSION[$name1][$name2] : null;
            } else {
                $value = isset($_SESSION[$name]) ? $_SESSION[$name] : null;
            }
        }
        return $value;
    }

    /**
     * 删除session数据
     *
     * @param string $name session名称
     * @param string $prefix 作用域（前缀）
     * @return void
     */
    public static function del($name, $prefix = '')
    {
        $prefix = $prefix ? $prefix : self::$prefix;
        if (strpos($name, '.')) {
            list($name1, $name2) = explode('.', $name);
            if ($prefix) {
                unset($_SESSION[$prefix][$name1][$name2]);
            } else {
                unset($_SESSION[$name1][$name2]);
            }
        } else {
            if ($prefix) {
                unset($_SESSION[$prefix][$name]);
            } else {
                unset($_SESSION[$name]);
            }
        }
    }

    /**
     * 清空session数据
     *
     * @param string $prefix 作用域（前缀）
     * @return void
     */
    public static function clear($prefix = '')
    {
        $prefix = $prefix ? $prefix : self::$prefix;
        if ($prefix) {
            unset($_SESSION[$prefix]);
        } else {
            $_SESSION = [];
        }
    }

    /**
     * 判断session数据
     *
     * @param string $name session名称
     * @param string $prefix
     *
     * @return bool
     * @internal param mixed $value session值
     */
    public static function has($name, $prefix = '')
    {
        $prefix = $prefix ? $prefix : self::$prefix;
        if (strpos($name, '.')) {
            // 支持数组
            list($name1, $name2) = explode('.', $name);
            return $prefix ? isset($_SESSION[$prefix][$name1][$name2]) : isset($_SESSION[$name1][$name2]);
        } else {
            return $prefix ? isset($_SESSION[$prefix][$name]) : isset($_SESSION[$name]);
        }
    }

    /**
     * 暂停session
     *
     * @return void
     */
    public static function pause()
    {
        // 暂停session
        session_write_close();
    }

    /**
     * 销毁session
     *
     * @return void
     */
    public static function destroy()
    {
        $_SESSION = [];
        session_unset();
        session_destroy();
    }

    /**
     * 重新生成session_id
     *
     * @return void
     */
    private static function regenerate()
    {
        session_regenerate_id();
    }
}
