<?php

namespace SsdPHP\View\Adaptor;

use SsdPHP\SsdPHP;

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
        'tpl_suffix'=>".html",
    );
    public static $Smarty = null;

    public function __construct($config=Null)
    {
        if(!empty($config)){
            self::$config = array_merge(self::$config,$config);
        }
        self::$Smarty = new \Smarty();
        self::$Smarty->setTemplateDir(self::$config['template_dir']);
        self::$Smarty->setCompileDir(self::$config['templates_c']);
        self::$Smarty->setCacheDir(self::$config['templates_config']);
        self::$Smarty->getPluginsDir(self::$config['templates_plugins']);
        self::$Smarty->force_compile  = self::$config['force_compile'];
        self::$Smarty->debugging      = self::$config['debugging'];
        self::$Smarty->caching        = self::$config['caching'];
        self::$Smarty->cache_lifetime = self::$config['cache_lifetime'];

    }

    /**
     * displays a Smarty template
     *
     * @param string $template   the resource handle of the template file or template object
     * @param mixed  $cache_id   cache id to be used with this template
     * @param mixed  $compile_id compile id to be used with this template
     * @param object $parent     next higher level of Smarty variables
     */
    public function display($template = null, $cache_id = null, $compile_id = null, $parent = null)
    {
        if(empty($template)){
            $template=SsdPHP::getAction()."/".SsdPHP::getController();
        }
        // display template
        self::$Smarty->display($template.self::$config['tpl_suffix'], $cache_id, $compile_id, $parent, 1);
    }

    /**
     * 引导在smarty
     * @author  xiaohuihui  <xzh_tx@163.com>
     * @param $name
     * @param $arguments
     * @return $this
     */
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
    public function render($tpl=''){
        if($tpl==''){
            $tpl=SsdPHP::getAction()."/".SsdPHP::getController();
        }
        return self::$Smarty->fetch($tpl.self::$config['tpl_suffix']);

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