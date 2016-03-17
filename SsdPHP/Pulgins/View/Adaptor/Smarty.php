<?php

namespace SsdPHP\Pulgins\View\Adaptor;

class Smarty
{


    public static $config = array(
        'templates_c'=>"templates_c",
        'template_dir'=>"templates",
        'templates_config'=>"templates_config",
        'templates_plugins'=>"templates_plugins",
        'force_compile'=>true,
        'debugging'=>true,
        'caching'=>true,
        'cache_lifetime'=>120,
    );
    public static $Smarty = null;

    public function __construct($config=Null)
    {
        if(!empty($config)){
            self::$config = array_merge(self::$config,$config);
        }
        self::$Smarty = new \Smarty();
    }

    public function __call($name, $arguments)
    {
        $count = count($arguments);
        switch($count){
            case 1:
                self::$Smarty->$name($arguments[0]);
                break;
            case 2:
                self::$Smarty->$name($arguments[0],$arguments[1]);
                break;
            case 3:
                self::$Smarty->$name($arguments[0],$arguments[1],$arguments[2]);
                break;
            case 4:
                self::$Smarty->$name($arguments[0],$arguments[1],$arguments[2],$arguments[3]);
                break;
        }

        return $this;
    }

    /**
     * render a .tpl
     */
    public function render($tpl){

        self::$Smarty->setTemplateDir(self::$config['template_dir']);
        self::$Smarty->setCompileDir(self::$config['templates_c']);
        self::$Smarty->setCacheDir(self::$config['templates_config']);
        self::$Smarty->getPluginsDir(self::$config['templates_plugins']);
        self::$Smarty->force_compile  = self::$config['force_compile'];
        self::$Smarty->debugging      = self::$config['debugging'];
        self::$Smarty->caching        = self::$config['caching'];
        self::$Smarty->cache_lifetime = self::$config['cache_lifetime'];
        return self::$Smarty->fetch("$tpl");

    }

    /**
     * 获取模版变量
     * author xiaohuihui <xzh_tx@163.com>
     * @param string $name
     * @return mixed
     */
    public function get($name=""){
        $tpl_var = self::$Smarty->getTemplateVars();
        if(isset($tpl_var[$name])){
            return $tpl_var[$name];
        }
    }
    /**
     * 302 redirect
     */
    public function redirect($url) {
        header('Location:'.$url);
        exit;
    }
}