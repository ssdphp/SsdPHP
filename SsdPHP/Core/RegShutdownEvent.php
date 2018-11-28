<?php

namespace SsdPHP\Core;
use SsdPHP\SsdPHP;

/**
 * Manage php shutdown events.
 * @author Lancer He <lancer.he@gmail.com>
 * @since  2014-08-21
 */
class RegShutdownEvent {

    /**
     * array to store user events.
     * @var array
     */
    private static $_events = array();


    /**
     * register shutdown
     */
    public static function register() {
        register_shutdown_function('SsdPHP\Core\RegShutdownEvent::call');
    }


    /**
     * Register event.
     * @return boolean
     */
    public static function add() {
        $event = func_get_args();

        if ( empty($event) ) {
            trigger_error("Register event need method.");
            return false;
        }

        if ( ! is_callable($event[0]) ) {
            trigger_error("Register event can not be call.");
            return false;
        }
        self::$_events[] = $event;
        return true;
    }


    /**
     * call event when you need.
     */
    public static function call() {
        /* 冲刷(flush)所有响应的数据给客户端并结束请求。 这使得客户端结束连接后，需要大量时间运行的任务能够继续运行 */
        !SsdPHP::isDebug() && php_sapi_name()=='fpm-fcgi' && fastcgi_finish_request();
        foreach (self::$_events as $event) {
            $callback = array_shift($event);
            call_user_func_array($callback, $event);
        }
    }
}