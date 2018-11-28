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
        if(!SsdPHP::getDebug()){
            if(((0 === strpos(PHP_SAPI,'cgi') || false !== strpos(PHP_SAPI,'fcgi'))) && function_exists("fastcgi_finish_request")){
                fastcgi_finish_request();
            }
        }
        foreach (self::$_events as $event) {
            $callback = array_shift($event);
            call_user_func_array($callback, $event);
        }
    }
}