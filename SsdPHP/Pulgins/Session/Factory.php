<?php

namespace SsdPHP\Pulgins\Session;
use SsdPHP\Core\Factory as SFactory,
    SsdPHP\Core\Config as SConfig;

class Factory
{

    private static $isStart = false;

    public static function getInstance($adapter = 'File', $config = null)
    {
        $className = __NAMESPACE__ . "\\Adaptor\\{$adapter}";
        return SFactory::getInstance($className, $config);
    }

    public static function Start( $sessionType="File" ,$config=null){

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
}
