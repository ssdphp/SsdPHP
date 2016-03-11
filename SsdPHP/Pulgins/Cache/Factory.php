<?php

namespace SsdPHP\Pulgins\Cache;
use SsdPHP\Core\Factory as SFactory,
    SsdPHP\Core\Config as SConfig;

class Factory
{

    public static function getInstance($adapter = 'Redis', $config = null)
    {
        if(empty($config)){
            $config = SConfig::getField("Redis","Main");
        }

        $className = __NAMESPACE__ . "\\Adaptor\\{$adapter}";
        return SFactory::getInstance($className, $config);
    }
}
