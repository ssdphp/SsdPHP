<?php

namespace SsdPHP\Session;
use SsdPHP\Core\Factory as SFactory,
    SsdPHP\Core\Config as SConfig;
use SsdPHP\SsdPHP;

/**
 * clone from Factory
 * Class Session
 * @package SsdPHP\Pulgins\Session
 */
class Session
{

    private static $isStart = false;
    private static $prefix  = "";

    public static function getInstance($adapter = 'Redis', $config = null)
    {
        $className = __NAMESPACE__ . "\\Adaptor\\{$adapter}";
        return SFactory::getInstance($className, $config);
    }

    public static function Start($config=null){

        if(self::$isStart === false){

            if(empty($config)){
                $config = SConfig::getField('session','home');
            }
            if(!empty($config['new_cache_expire'])) {
                session_cache_expire($config['new_cache_expire']);
            }
            if(!empty($config['cookie_path'])){
                ini_set('session.cookie_path', '/');
            }
            ini_set('session.cookie_httponly', !isset($config['httponly']) ? true : $config['httponly']);

            if (isset($config['domain'])) {
                ini_set('session.cookie_domain', $config['domain']);
            }

            if (isset($config['cache_limiter'])) {
                session_cache_limiter($config['cache_limiter']);
            }

            $sessionName = empty($config['session_name']) ? 'SSDPHPSESSID' : $config['session_name'];
            $preSessionName = session_name($sessionName);

            if(!empty($_GET[$sessionName])) {
                session_id($_GET[$sessionName]);
            }elseif(!empty($_POST[$sessionName])) {
                session_id($_POST[$sessionName]);
            }elseif(!empty($_SERVER[$sessionName])) {
                session_id($_SERVER[$sessionName]);
            }elseif(!empty($_COOKIE[$sessionName])) {
                session_id($_COOKIE[$sessionName]);
            }
            //print_r($_GET);

            if (!empty($config['sessionType'])) {
                $handler = self::getInstance($config['sessionType'], $config);
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
    public static function regenerate()
    {
        session_regenerate_id();
        $sid = session_id();
        //  close the old and new sessions
        session_write_close();
        //  re-open the new session
        session_id($sid);
        session_start();
    }


    public static function session_id(){
        return session_id();
    }


}
//解决debug模式sessionid重新生成的bug,引导在错误处理
if(SsdPHP::getDebug()==false){
    set_error_handler('SsdPHP\Core\Error::error_handler',E_ALL);

}