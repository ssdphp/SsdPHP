<?php

namespace SsdPHP\Pulgins\DataBase;
use SsdPHP\Core\Factory as SFactory,
    SsdPHP\Core\Config as SConfig;

class Factory
{

    public static function getInstance($adapter = 'Mysql', $config = null)
    {
        if(empty($config)){
            $config = SConfig::getField("Mysql","Main");
        }
        $className = __NAMESPACE__ . "\\Adaptor\\{$adapter}";
        return SFactory::getInstance($className, $config);
    }
}
