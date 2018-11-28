<?php

namespace SsdPHP\Pulgins\View;
use SsdPHP\Core\Factory as SFactory,
    SsdPHP\SsdPHP,
    SsdPHP\Core\Config as SConfig;

class Factory
{

    public static function getInstance($adapter = 'Smarty', $config = array())
    {

        if(empty($config)){
            $appdir = realpath(SsdPHP::getRootPath().SsdPHP::getAppDir());
            $model = SsdPHP::getModel();
            $templates_c = $appdir.DIRECTORY_SEPARATOR.$model.DIRECTORY_SEPARATOR."templates_c/";
            $template_dir= $appdir.DIRECTORY_SEPARATOR.$model.DIRECTORY_SEPARATOR."templates/";
            $templates_config= $appdir.DIRECTORY_SEPARATOR.$model.DIRECTORY_SEPARATOR."templates_config/";
            $templates_plugins= $appdir.DIRECTORY_SEPARATOR.$model.DIRECTORY_SEPARATOR."templates_plugins/";
            $config = SConfig::get("View");
            if(!empty($config['Adaptor']))
                $adapter=$config['Adaptor'];
            $config = array_merge($config,array(
                'templates_c'=>$templates_c,
                'template_dir'=>$template_dir,
                'templates_config'=>$templates_config,
                'templates_plugins'=>$templates_plugins,
            ));
        }
        $className = __NAMESPACE__ . "\\Adaptor\\{$adapter}";
        return SFactory::getInstance($className, $config);
    }


}
