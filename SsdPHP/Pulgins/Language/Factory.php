<?php

namespace SsdPHP\Pulgins\Language;
use SsdPHP\Core\Factory as SFactory,
    SsdPHP\Core\Config as SConfig;
use SsdPHP\SsdPHP;

class Factory
{

    public function __construct()
    {

    }

    public static function getInstance($adapter = 'Language', $config = null)
    {
        if(isset(self::$_instance)){
            return self::$_instance;
        }
        if(empty($config)){
            $config = SConfig::getField("Mysql","Main");
        }
        if(!empty($config['prefix']))
            self::$prefix = $config['prefix'];

        $className = __NAMESPACE__ . "\\Adaptor\\{$adapter}";
        self::$_instance = SFactory::getInstance($className, $config);
    }

}
