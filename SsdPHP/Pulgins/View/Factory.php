<?php

namespace SsdPHP\Pulgins\View;
use SsdPHP\Core\Factory as SFactory,
    SsdPHP\SsdPHP,
    SsdPHP\Core\Config as SConfig;

class Factory
{

    public static function getInstance($adapter = 'Smarty', $config = null)
    {

        if(empty($config)){
            $appdir = SsdPHP::getRootPath().SsdPHP::getAppDir();
            $model = SsdPHP::getModel();
            $templates_c = $appdir.DIRECTORY_SEPARATOR.$model.DIRECTORY_SEPARATOR."templates_c";
            $template_dir= $appdir.DIRECTORY_SEPARATOR.$model.DIRECTORY_SEPARATOR."templates";
            $templates_config= $appdir.DIRECTORY_SEPARATOR.$model.DIRECTORY_SEPARATOR."templates_config";
            $templates_plugins= $appdir.DIRECTORY_SEPARATOR.$model.DIRECTORY_SEPARATOR."templates_plugins";
            $config = SConfig::get($adapter,array(
                'templates_c'=>$templates_c,
                'template_dir'=>$template_dir,
                'templates_config'=>$templates_config,
                'templates_plugins'=>$templates_plugins,
                'force_compile'=>true,
                'debugging'=>false,
                'caching'=>true,
                'cache_lifetime'=>120,
                'tpl_suffix'=>".html",
                'Adaptor'=>"Tpl",
            ));
            if($config['Adaptor'])
                $adapter=$config['Adaptor'];
        }
        $className = __NAMESPACE__ . "\\Adaptor\\{$adapter}";
        return SFactory::getInstance($className, $config);
    }


}
