<?php
namespace SsdPHP\Core;
use SsdPHP\SsdPHP;

/**
 * Class Route
 * @author xiaohuihui <xzh_tx@163.com>
 * @package SsdPHP\Pulgins
 */
class Route{
    private static $_Routes=array();

    public static function set($route){
        self::$_Routes = $route;
        if(!empty(self::$_Routes)){
            self::parse();
        }
    }

    /**
     * 支持cli
     * author xiaohuihui <xzh_tx@163.com>
     */
    private static function parse(){
        if(PHP_SAPI=='cli'){
            $_SERVER['PATH_INFO']=isset($_SERVER['argv'][1]) ? $_SERVER['argv'][1] : '/';
        }
        $REQUEST_URI = !empty($_SERVER['PATH_INFO'])?$_SERVER['PATH_INFO']:$_SERVER['REQUEST_URI'];

        foreach(self::$_Routes as $pattern=>$pathInfo){

            preg_match("$pattern",$REQUEST_URI,$r);
            if(isset($r[0]) && !empty($r)){
                $url = preg_replace($pattern,$pathInfo,$REQUEST_URI);
                SsdPHP::setPathInfo($url);
                break;
            }
        }
        return ;
    }
}
