<?php
/**
 * User: shenzhe
 * Date: 13-6-17
 * 对列接口
 */
namespace SsdPHP\Pulgins\Queue;

use SsdPHP\Core\Factory as SFactory,
    SsdPHP\Core\Config as SConfig;

class Factory
{
    public static function getInstance($adaptor = 'Redis', $config = null)
    {
    	if(empty($config)) {
    		$config = SConfig::get('queue');
    		if(!empty($config['Adaptor'])) {
    		}
    	}
        $className = __NAMESPACE__ . "\\Adaptor\\{$adaptor}";
        return SFactory::getInstance($className, $config);
    }
}
