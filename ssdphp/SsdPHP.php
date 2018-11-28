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
     * @desc /App/$model/
     * @var string
     */
    private static $model;

    /**
     * @desc /App/$model/$controller/
     * @var string
     */
    private static $controller;

    /**
     * @desc /App/$model/$controller/$action::class->c_method
     * @var string
     */
    private static $action;

    /**
     * @desc /App/$model/$controller/$action::class->$action_prefix method
     * @var string
     */
    private static $action_prefix = "c_";

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
    private static $appdir="../../../";

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
    public static function getDebug()
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
     * 加载 /App/*
     * 加载 /SsdPHP/*
     * @param $class
     */
    public static function autoLoad($classname){

        if(empty($classname)){
            return false;
        }
        if($classname[0]!="\\"){
            $classname="\\".$classname;
        }
        if(isset(self::$_class[$classname])){
            return true;
        }

        $filename = str_replace("\\","/",$classname).".php";
        $file = self::getAppDir().$filename;
        if(file_exists($file)) {
            self::$_class[$classname]=true;
            require($file);
            return true;
        }
        $file = self::getRootPath().str_replace("/SsdPHP/","",$filename);
        if(file_exists($file)) {
            self::$_class[$classname]=true;
            require($file);
            return true;
        }
        return false;
    }

    /**
     * 引导回调
     */
    public static function Bootstrap($fun=null){
        //自动加载
        spl_autoload_register('self::autoLoad');
        if(is_callable($fun)){
            call_user_func($fun);
        }
        return new SsdPHP();
    }

    /**运行
     * @param string $path
     * @return mixed
     */
    public function Run(){
        $splitFlag = preg_quote(self::$splitFlag,"/");
        $path_array = array();
        $url_param = array();
        if(!empty(self::$pathInfo)){
            $url = self::$pathInfo;
        }else{
            if(PHP_SAPI=='cli'){
                $url=isset($_SERVER['argv'][1]) ? $_SERVER['argv'][1] : '';
            }elseif(!empty($_GET['PATH_INFO'])){
                $url= $_GET['PATH_INFO'];
            }elseif(!empty($_SERVER['REQUEST_URI'])){
                $url= $_SERVER["REQUEST_URI"];
            }elseif(!empty($_SERVER['PATH_INFO'])){
                $url= $_SERVER["PATH_INFO"];
            }else{
                if(self::getDebug() == true){
                    throw new \Exception('path not set in params or server.path_info, server.request_uri,$_GET.PATH_INFO,cli.argv');
                }else{
                    return false;
                }
            }
        }
        if(isset($_GET['PATH_INFO']))
            unset($_GET['PATH_INFO']);
        if(!empty($url)){
            if($url[0]=="/")
                $url=substr($url,1);
            $path_array = preg_split("/[$splitFlag]/",$url,-1,PREG_SPLIT_NO_EMPTY);
            if(!empty($path_array[2]) && stripos($path_array[2],'.')!==false){
                $path_array[2] = preg_replace('/(.*?)\..*/i','$1',$path_array[2]);
            }
            $url_param = array_slice($path_array, 3);
        }
        if(!empty($url_param)){
            $var = array();
            preg_replace_callback("/(\w+?)\/([^\/?]+)/", function ($match) use (&$var) {
                $var[$match[1]] = strip_tags($match[2]);
            },implode(self::$splitFlag, $url_param));
            $_GET       =  array_merge($var,$_GET);
            $_REQUEST   =  array_merge($_GET,$_POST);
        }
        self::$model        = self::$model?self::$model:ucfirst(strtolower(!empty($path_array[0]) ? $path_array[0] : self::$defaultModel));
        self::$controller   = self::$controller?self::$controller:strtolower(!empty($path_array[1]) ? $path_array[1] : self::$defaultController);
        self::$action       = self::$action?self::$action:strtolower(!empty($path_array[2]) ? $path_array[2] : self::$defaultAction);
        //find APP/namespace classname
        $classname  = "\\App\\".self::$model."\\Controller\\"."C".self::getController();
        //---start
        if(!class_exists($classname,true)){
            if(self::getDebug() == true){
                throw new \Exception("classname:[ $classname ] no exists ");
            }else{
                return false;
            }
        }
        $classInstance = new $classname();
        if(!method_exists($classInstance,$method = "c_".self::getAction())){
            if(self::getDebug() == true){
                throw new \Exception("method[$method] does not exists in class[$classname]");
            }else{
                return false;
            }
        }
        return call_user_func(array(&$classInstance,$method),$path_array);
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
        self::$_rootpath = __DIR__.DIRECTORY_SEPARATOR;
        return self::$_rootpath;
    }

    /**
     * @param string $path
     * @return bool
     */
    public static function setRootPath($path=""){
        if(!empty($path) && is_dir($path)){
            self::$_rootpath = $path;
            return true;
        }
        return false;
    }
}