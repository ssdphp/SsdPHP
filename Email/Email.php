<?php

namespace SsdPHP\Email;
use SsdPHP\Core\Factory as SFactory,
    SsdPHP\Core\Config as SConfig;

class Email
{

    public static function getInstance($adapter = 'Email', $config = null)
    {
        if(empty($config)){
            $config = SConfig::getField($adapter,"Main");
        }

        $className = __NAMESPACE__ . "\\Adaptor\\{$adapter}";
        return SFactory::getInstance($className, $config);
    }
}
