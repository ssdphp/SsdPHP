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
    private static $defaultModel        ="home";

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
    private static $debug=true;
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
     * 注册自动加载
     */
    public static function registerAutoLoad(){

        /*自动加载*/
        spl_autoload_register('self::autoLoad');
        self::registerComposerLoader();
        return true;
    }
    // 类名映射
    protected static $map = array();
    // 加载列表
    protected static $load = array();
    // 命名空间
    protected static $namespace = array();
    // PSR-4
    private static $prefixLengthsPsr4 = array();
    private static $prefixDirsPsr4    = array();
    // PSR-0
    private static $prefixesPsr0 = [];
    // 注册composer自动加载
    private static function registerComposerLoader()
    {

        if (is_file(self::getRootPath() . 'vendor/composer/autoload_namespaces.php')) {
            $map = require self::getRootPath() . 'vendor/composer/autoload_namespaces.php';
            foreach ($map as $namespace => $path) {
                self::$prefixesPsr0[$namespace[0]][$namespace] = (array) $path;
            }
        }

        if (is_file(self::getRootPath() . 'vendor/composer/autoload_psr4.php')) {
            $map = require self::getRootPath() . 'vendor/composer/autoload_psr4.php';
            foreach ($map as $namespace => $path) {
                $length = strlen($namespace);
                if ('\\' !== $namespace[$length - 1]) {
                    throw new \InvalidArgumentException("A non-empty PSR-4 prefix must end with a namespace separator.");
                }
                self::$prefixLengthsPsr4[$namespace[0]][$namespace] = $length;
                self::$prefixDirsPsr4[$namespace]                   = (array) $path;
            }
        }

        if (is_file(self::getRootPath() . 'vendor/composer/autoload_classmap.php')) {
            $classMap = require self::getRootPath() . 'vendor/composer/autoload_classmap.php';
            if ($classMap) {
                self::addMap($classMap);
            }
        }

        if (is_file( self::getRootPath() . 'vendor/composer/autoload_files.php')) {
            $includeFiles = require self::getRootPath() . 'vendor/composer/autoload_files.php';
            foreach ($includeFiles as $fileIdentifier => $file) {
                self::composerRequire($fileIdentifier, $file);
            }
        }
    }

    // 注册classmap
    public static function addMap($class, $map = '')
    {
        if (is_array($class)) {
            self::$_class = array_merge(self::$map, $class);
        } else {
            self::$_class[$class] = $map;
        }
    }

    private static function composerRequire($fileIdentifier, $file)
    {
        if (empty($GLOBALS['__composer_autoload_files'][$fileIdentifier])) {
            require $file;
            $GLOBALS['__composer_autoload_files'][$fileIdentifier] = true;
        }
    }
    /*
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

        self::$model  	    = !empty($path_array[0]) ? $path_array[0] : self::$defaultModel ;
        self::$controller   = !empty($path_array[1]) ? $path_array[1] : self::$defaultController ;
        self::$action  	    = !empty($path_array[2]) ? $path_array[2] : self::$defaultAction ;
        $classname  = self::$model."\\controller\\".self::$controller;
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
                // 前置调用
                if($class->hasMethod('_before_'.self::$action)) {
                    $before =   $class->getMethod('_before_'.self::$action);
                    if($before->isPublic()) {
                        $before->invoke($classInstance);
                    }
                }
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

                // 后置调用
                if($class->hasMethod('_after_'.self::$action)) {
                    $before =   $class->getMethod('_after_'.self::$action);
                    if($before->isPublic()) {
                        $before->invoke($classInstance);
                    }
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

    public static function getRootPath(){
        if(self::$_rootpath){
            return self::$_rootpath;
        }
        self::$_rootpath = dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR;
        return self::$_rootpath;
    }
    public static function setRootPath($path=""){
        if(!empty($path) && is_dir($path)){
            self::$_rootpath = $path;
        }
        self::$_rootpath = dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR;
        return self::$_rootpath;
    }

    /**
     * 自动加载类
     * @param $class
     */
    public static function autoLoad($class){

        if(isset(self::$_class[$class])){
            return true;
        }
        /* composer自动加载 */
        if($file = self::findFileInComposer($class)){
            require($file);
            self::$_class[$class]=true;
            return true;
        }
        if(!empty($class)){
            $class = str_replace("\\",DIRECTORY_SEPARATOR,$class);
        }
        $file = self::getRootPath().$class.".php";
        if(file_exists($file)) {
            self::$_class[$class]=true;
            require($file);
            return ;
        }
    }

    /**
     * 查找Composer
     * @param $class
     * @param string $ext
     * @return bool|string
     */
    private static function findFileInComposer($class, $ext = '.php')
    {
        // PSR-4 lookup
        $logicalPathPsr4 = strtr($class, '\\', DIRECTORY_SEPARATOR) . $ext;

        $first = $class[0];
        if (isset(self::$prefixLengthsPsr4[$first])) {
            foreach (self::$prefixLengthsPsr4[$first] as $prefix => $length) {
                if (0 === strpos($class, $prefix)) {
                    foreach (self::$prefixDirsPsr4[$prefix] as $dir) {
                        if (file_exists($file = $dir . DIRECTORY_SEPARATOR . substr($logicalPathPsr4, $length))) {
                            return $file;
                        }
                    }
                }
            }
        }
        // PSR-0 lookup
        if (false !== $pos = strrpos($class, '\\')) {
            // namespaced class name
            $logicalPathPsr0 = substr($logicalPathPsr4, 0, $pos + 1)
                . strtr(substr($logicalPathPsr4, $pos + 1), '_', DIRECTORY_SEPARATOR);
        } else {
            // PEAR-like class name
            $logicalPathPsr0 = strtr($class, '_', DIRECTORY_SEPARATOR) . $ext;
        }

        if (isset(self::$prefixesPsr0[$first])) {
            foreach (self::$prefixesPsr0[$first] as $prefix => $dirs) {
                if (0 === strpos($class, $prefix)) {
                    foreach ($dirs as $dir) {
                        if (file_exists($file = $dir . DIRECTORY_SEPARATOR . $logicalPathPsr0)) {
                            return $file;
                        }
                    }
                }
            }
        }
        // Remember that this class does not exist.
        return self::$map[$class] = false;
    }
}