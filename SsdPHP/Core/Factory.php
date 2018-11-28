<?php
// +----------------------------------------------------------------------
// | www
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.ssdphp.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: xiaohuihui <xzh_tx@163.com> //
// +----------------------------------------------------------------------

namespace SsdPHP\Core;

Class Factory
{
    private static $_instance = array();

    public static function getInstance($className, $params = null)
    {
        $keyName = $className;
        if (!empty($params['_prefix'])) {
            $keyName .= $params['_prefix'];
        }
        if (isset(self::$_instance[$keyName])) {
            return self::$_instance[$keyName];
        }
        if (!\class_exists($className)) {
            throw new \Exception("not find class {$className}");
        }
        if (empty($params)) {
            self::$_instance[$keyName] = new $className();
        } else {
            self::$_instance[$keyName] = new $className($params);
        }
        return self::$_instance[$keyName];
    }
}