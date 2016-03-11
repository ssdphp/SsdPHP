<?php
// +----------------------------------------------------------------------
// | server.uarein.com
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.uarein.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: xiaohuihui <598550105@qq.com> //
// +----------------------------------------------------------------------
namespace pulgins;
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
    private static $_events = array(
        //致命错误的处理
        array('SError::fatalError','1'),
        //日志处理
        array('SLog::UserActionLog','1')
    );


    /**
     * register shutdown
     */
    public static function register() {
        register_shutdown_function(array('SRegShutdownEvent', 'call'));
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
        foreach (self::$_events as $event) {
            $callback = array_shift($event);
            call_user_func_array($callback, $event);
        }
    }
}