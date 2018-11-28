<?php

namespace SsdPHP\View;
use SsdPHP\SsdPHP,
    SsdPHP\Core\Config;

class Factory
{

    public static function getInstance($adapter = 'Tpl', $config = array())
    {

        if(empty($config)){
            $model = SsdPHP::getModel();
            $appdir = SsdPHP::getAppDir()."/App/".$model.DIRECTORY_SEPARATOR;

            $templates_c = $appdir."Templates_c/";
            $template_dir= $appdir."Templates/";
            $templates_config= $appdir."Templates_config/";
            $templates_plugins= $appdir."Templates_plugins/";

            $default_config = array(
                'force_compile' => true,
                'debugging' => true,
                'caching' => false,
                'cache_lifetime' => 120,
                'tpl_suffix' => ".html",
                'left_delimiter'=>'{{',
                'right_delimiter'=>'}}',
                'Adaptor' => "Tpl",//自带的Tpl，和Smarty
            );

            $setConfigView = Config::getField('view',"view");

            if(!empty($setConfigView)) {
                $default_config = array_merge($default_config, $setConfigView);
            }
            if(!empty($default_config['Adaptor'])){
                $adapter=$default_config['Adaptor'];
            }
            $config = array_merge($default_config,array(
                'templates_c'=>$templates_c,
                'template_dir'=>$template_dir,
                'templates_config'=>$templates_config,
                'templates_plugins'=>$templates_plugins,
            ));
        }

        $className = __NAMESPACE__ . "\\Adaptor\\{$adapter}";

        return new $className($config);
    }


}
