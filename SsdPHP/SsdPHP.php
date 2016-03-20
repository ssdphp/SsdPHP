<?php
/*{{{LICENSE
+-----------------------------------------------------------------------+
| SsdPHP Framework                                                   |
+-----------------------------------------------------------------------+
| This program is free software; you can redistribute it and/or modify  |
| it under the terms of the GNU General Public License as published by  |
| the Free Software Foundation. You should have received a copy of the  |
| GNU General Public License along with this program.  If not, see      |
| http://www.gnu.org/licenses/.                                         |
| Copyright (C) 2015-2020. All Rights Reserved.                         |
+-----------------------------------------------------------------------+
| Supports: http://www.SsdPHP.com                                    |
+-----------------------------------------------------------------------+
}}}*/

namespace SsdPHP;

class SsdPHP{
    /**
     * @var string
     */
    private static $model;

    /**
     * @var string
     */
    private static $controller;

    /**
     * @var string
     */
    private static $action;

    /**
     * @var string
     */
    private static $defaultModel        ="Home";

    /**
     * @var string
     */
    private static $defaultController   ="index";

    /**
     * @var string
     */
    private static $defaultAction       ="index";

    /**
     * @var string
     */
    private static $splitFlag="/";

    /**
     * @var string
     */
    private static $pathInfo="";

    /**
     * @var bool
     */
    private static $debug=false;
    /**
     * @var null
     */
    private static $_class=null;

    /**
     * @var string
     */
    private static $appdir="App";
    /**
     * 注册自动加载
     */
    public static function registerAutoLoad(){
        /*自动加载*/
        spl_autoload_register('self::autoLoad');
        return true;
    }
    /**
     * @return string
     */
    public static function getAction()
    {
        return self::$action;
    }

    /**
     * @param string $action
     */
    public static function setAction($action)
    {
        self::$action = $action;
    }

    /**
     * @return boolean
     */
    public static function isDebug()
    {
        return self::$debug;
    }

    /**
     * @param boolean $debug
     */
    public static function setDebug($debug=true)
    {
        self::$debug = $debug;
    }

    /**
     * @return string
     */
    public static function getController()
    {
        return self::$controller;
    }

    /**
     * @param string $controller
     */
    public static function setController($controller)
    {
        self::$controller = $controller;
    }

    /**
     * @return string
     */
    public static function getDefaultAction()
    {
        return self::$defaultAction;
    }

    /**
     * @param string $defaultAction
     */
    public static function setDefaultAction($defaultAction)
    {
        self::$defaultAction = $defaultAction;
    }

    /**
     * @return string
     */
    public static function getDefaultController()
    {
        return self::$defaultController;
    }

    /**
     * @param string $defaultController
     */
    public static function setDefaultController($defaultController)
    {
        self::$defaultController = $defaultController;
    }

    /**
     * @return string
     */
    public static function getDefaultModel()
    {
        return self::$defaultModel;
    }

    /**
     * @param string $defaultModel
     */
    public static function setDefaultModel($defaultModel)
    {
        self::$defaultModel = $defaultModel;
    }

    /**
     * @return string
     */
    public static function getModel()
    {
        return self::$model;
    }

    /**
     * @param string $model
     */
    public static function setModel($model)
    {
        self::$model = $model;
    }

    /**
     * @return string
     */
    public static function getPathInfo()
    {
        return self::$pathInfo;
    }

    /**
     * @param string $pathInfo
     */
    public static function setPathInfo($pathInfo)
    {
        self::$pathInfo = $pathInfo;
    }

    /**
     * @return string
     */
    public static function getSplitFlag()
    {
        return self::$splitFlag;
    }

    /**
     * @param string $splitFlag
     */
    public static function setSplitFlag($splitFlag)
    {
        self::$splitFlag = $splitFlag;
    }

    /**
     * @param string $path
     */
    public static function setAppDir($path = ""){
        self::$appdir = $path;
    }

    /**
     * @return string
     */
    public static function getAppDir(){
        return self::$appdir;
    }


    /**
     * 自动加载类
     * @param $class
     */
    public static function autoLoad($class){

        if(isset(self::$_class[$class])){
            return true;
        }
        if(!empty($class)){
            $class = str_replace("\\",DIRECTORY_SEPARATOR,$class);
        }
        $file = self::getRootPath().$class.".php";
        if(file_exists($file)) {
            self::$_class[$class]=true;
            require($file);
        }
        return ;
    }

    /**
     * 引导回调
     */
    public static function Bootstrap($fun=Null){
        self::registerAutoLoad();
        if(is_callable($fun)){
            call_user_func($fun);
        }
        return new SsdPHP();
    }

    /**运行
     * @param string $path
     * @return mixed
     */
    public static function Run($path=""){
        $splitFlag = preg_quote(self::$splitFlag,"/");
        $path_array = array();
        $url_param = array();
        if(!empty($path)){
            if($path[0]=="/")$path=substr($path,1);
            $path_array = preg_split("/[$splitFlag\/]/",$path,-1);
        }else{
            if(!empty(self::$pathInfo)){
                $url = self::$pathInfo;
            }else{
                if(!empty($_GET['PATH_INFO'])){
                    $url = $_GET['PATH_INFO'];
                    unset($_GET['PATH_INFO']);
                }else{
                    if(PHP_SAPI=='cli'){
                        $_SERVER['PATH_INFO']=isset($_SERVER['argv'][1]) ? $_SERVER['argv'][1] : '';
                    }
                    $url = !empty($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : "";
                }
            }

            if(!empty($url)){
                if($url[0]=="/")$url=substr($url,1);
                $path_array = preg_split("/[$splitFlag\/]/",$url,-1);
                $url_param = array_slice($path_array, 3);
            }
        }

        self::setModel(!empty($path_array[0]) ? $path_array[0] : self::getDefaultModel());
        self::setController(!empty($path_array[1]) ? $path_array[1] : self::getDefaultController());
        self::setAction(!empty($path_array[2]) ? $path_array[2] : self::getDefaultAction());

        $classname  = trim(self::getAppDir()."\\".self::getModel()."\\controller\\".self::getController(),"\\");
        try{
            if(!class_exists($classname,true)){
                throw new \ReflectionException("classname:[ $classname ] no exists ");
            }
            $classInstance = new $classname();
            $method =   new \ReflectionMethod($classInstance, self::$action);
            if($method->isPublic() && !$method->isStatic()) {
                $class  =   new \ReflectionClass($classInstance);
                /* {{{ url参数处理 合并参数在get,request里面 */
                $var = array();
                if(!empty($url_param)){
                    preg_replace_callback('/(\w+)\/([^\/]+)/', function($match) use(&$var){$var[$match[1]]=strip_tags($match[2]);}, implode(self::getSplitFlag(),$url_param));
                    $_GET   =  array_merge($var,$_GET);
                }
                $_REQUEST = array_merge($_POST,$_GET);
                /* }}} end url参数处理 */

                //主方调用
                if($method->getNumberOfParameters()>0){
                    $params =  $method->getParameters();
                    if(isset($params[1])){
                        throw new \ReflectionException($method->class."::{$method->name} must be only one param!");
                    }
                    $p = array($params[0]->getName()=>$path_array);
                    $r = $method->invokeArgs($classInstance,$p);
                }else{
                    $r = $method->invoke($classInstance);
                }
                return $r;
            }else{
                throw new \ReflectionException("must be PUBLIC and NOT STATIC method");
            }
        }catch(\ReflectionException $e){
            echo "<!--ERROR:{$e->getMessage()}-->";
        }
        return false;
    }

    private static $_rootpath="";

    /**
     * composer没有的文件，将在rootpath自动加载
     * @return string
     */
    public static function getRootPath(){
        if(self::$_rootpath){
            return self::$_rootpath;
        }
        self::$_rootpath = dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR;
        return self::$_rootpath;
    }

    /**
     * @param string $path
     * @return string
     */
    public static function setRootPath($path=""){
        if(!empty($path) && is_dir($path)){
            self::$_rootpath = $path;
            return true;
        }
        self::$_rootpath = dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR;
        return self::$_rootpath;
    }
}